<?php

namespace App\Http\Controllers;

use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use App\Helpers\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PemanggilAntrianController extends Controller
{
    /**
     * Router utama untuk semua loket
     */
    public function index(Request $request)
    {
        $show = $request->get('show');
        
        // Map show parameter ke type
        $typeMap = [
            'panggil_loket' => 'Loket',
            'panggil_loket_vip' => 'LoketVIP',
            'panggil_cs' => 'CS',
            'panggil_cs_vip' => 'CSVIP',
            'panggil_apotek' => 'Apotek',
        ];
        
        $type = $typeMap[$show] ?? null;
        
        if (!$type) {
            return redirect()->route('anjungan.index');
        }
        
        return $this->renderPanggil($request, $type);
    }

    /**
     * ✅ FIXED: Universal render logic dengan proper action detection
     */
    private function renderPanggil(Request $request, $type)
    {
        // Get config dari helper
        $config = AntrianHelper::getByType($type);
        
        if (!$config) {
            abort(404, 'Loket tidak ditemukan');
        }
        
        $currentLoket = $request->get('loket', '1');
        
        // ✅ FIX 1: Handle form lompat antrian
        if ($request->has('antrian') && $request->filled('antrian')) {
            $this->lompatAntrian($type, $request->antrian, $currentLoket);
            return redirect($request->url() . '?show=' . $config['show'] . '&loket=' . $currentLoket . '&skip_audio=1');
        }
        
        // ✅ FIX 2: Handle tombol "Berikutnya" (HANYA jika ada action=next)
        if ($request->get('action') === 'next') {
            Log::info('🔵 Tombol BERIKUTNYA diklik', [
                'type' => $type,
                'loket' => $currentLoket
            ]);
            
            $this->panggilAntrianBerikutnya($type, $currentLoket);
            
            return redirect($request->url() . '?show=' . $config['show'] . '&loket=' . $currentLoket . '&skip_audio=1');
        }
        
        // Ambil setting loket dari database
        $fieldPrefix = $this->getFieldPrefix($type);
        $settingLoket = MliteSetting::getSetting('anjungan', "antrian_{$fieldPrefix}", '1,2,3');
        $loketList = array_filter(array_map('trim', explode(',', $settingLoket)));
        
        // Ambil nomor antrian terkini yang sedang dipanggil
        $nomorTerkini = $this->getNomorAntrianTerkini($type);
        
        // Ambil total antrian hari ini
        $totalAntrian = AntrianLoket::getNomorTerakhir($type);
        
        // Hitung statistik antrian hari ini
        $hitungAntrian = AntrianLoket::where('type', $type)
            ->hariIni()
            ->get();
        
        return view('anjungan.pemanggil.index', [
            'config' => $config,
            'loket' => $loketList,
            'antrian' => $nomorTerkini,
            'noantrian' => $totalAntrian,
            'hitung_antrian' => $hitungAntrian,
            'namaloket' => $config['prefix'],
            'panggil_loket' => $config['show'],
            'show' => $request->get('show'),
            'current_loket' => $currentLoket,
        ]);
    }

    /**
     * ✅ DEBUG: Panggil antrian berikutnya dengan logging
     */
    private function panggilAntrianBerikutnya($type, $loket)
    {
        $nomorTerkini = $this->getNomorAntrianTerkini($type);
        $nomorBerikutnya = $nomorTerkini + 1;
        
        Log::info('🟢 Panggil Berikutnya START', [
            'type' => $type,
            'nomor_terkini' => $nomorTerkini,
            'nomor_berikutnya' => $nomorBerikutnya,
            'loket' => $loket
        ]);
        
        // Cek apakah antrian ada
        $antrian = AntrianLoket::where('type', $type)
            ->where('noantrian', $nomorBerikutnya)
            ->whereDate('postdate', today())
            ->first();
        
        if (!$antrian) {
            Log::warning('🔴 Antrian TIDAK DITEMUKAN!', [
                'type' => $type,
                'noantrian' => $nomorBerikutnya,
                'date' => today()->toDateString()
            ]);
            return;
        }
        
        Log::info('🟡 Antrian ditemukan, akan di-update', [
            'id' => $antrian->kd,
            'old_end_time' => $antrian->end_time,
            'old_status' => $antrian->status
        ]);
        
        // Update database antrian
        $result = AntrianLoket::where('type', $type)
            ->where('noantrian', $nomorBerikutnya)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1'
            ]);
        
        Log::info('🟢 Update database result', [
            'rows_affected' => $result,
            'new_end_time' => now()->format('H:i:s')
        ]);
        
        // Update settings
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $nomorBerikutnya);
        
        Log::info('🟢 Panggil Berikutnya DONE', [
            'final_nomor' => $nomorBerikutnya
        ]);
    }

    /**
     * Lompat ke nomor antrian tertentu
     */
    private function lompatAntrian($type, $nomor, $loket)
    {
        if (!is_numeric($nomor) || $nomor < 1) {
            return;
        }
        
        // Update database antrian
        AntrianLoket::where('type', $type)
            ->where('noantrian', $nomor)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1'
            ]);
        
        // Update settings
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $nomor);
    }

    /**
     * Get nomor antrian terkini dari settings
     */
    private function getNomorAntrianTerkini($type)
    {
        $fieldPrefix = $this->getFieldPrefix($type);
        return (int) MliteSetting::getSetting('anjungan', "panggil_{$fieldPrefix}_nomor", 0);
    }

    /**
     * Get field prefix untuk settings database
     */
    private function getFieldPrefix($type)
    {
        return match($type) {
            'Loket' => 'loket',
            'LoketVIP' => 'loket_vip',
            'CS' => 'cs',
            'CSVIP' => 'cs_vip',
            'Apotek' => 'apotek',
            default => strtolower($type)
        };
    }

    // ===========================================
    // API ENDPOINTS
    // ===========================================

    /**
     * API: Set panggil antrian
     */
    public function setPanggil(Request $request)
    {
        $validated = $request->validate([
            'noantrian' => 'required|numeric',
            'type' => 'required|string',
            'loket' => 'nullable|string',
        ]);
        
        $type = $this->normalizeType($validated['type']);
        $noantrian = $validated['noantrian'];
        $loket = $validated['loket'] ?? '1';
        
        Log::info('🔵 API setPanggil called', [
            'type' => $type,
            'noantrian' => $noantrian,
            'loket' => $loket
        ]);
        
        // Update status antrian
        $result = AntrianLoket::where('type', $type)
            ->where('noantrian', $noantrian)
            ->whereDate('postdate', today())
            ->update([
                'status' => '1',
                'loket' => $loket,
                'end_time' => now()->format('H:i:s')
            ]);
        
        Log::info('🟢 API setPanggil result', [
            'rows_affected' => $result,
            'new_end_time' => now()->format('H:i:s')
        ]);
        
        // Update settings
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $noantrian);
        
        return response()->json([
            'status' => (bool)$result,
            'message' => $result ? 'Panggil berhasil' : 'Data tidak ditemukan'
        ]);
    }

    /**
     * API: Simpan nomor rekam medis
     */
    public function simpanNoRM(Request $request)
    {
        $validated = $request->validate([
            'noantrian' => 'required|numeric',
            'type' => 'required|string',
            'no_rkm_medis' => 'required|size:6|numeric'
        ]);
        
        $type = $this->normalizeType($validated['type']);
        
        $result = AntrianLoket::where('noantrian', $validated['noantrian'])
            ->where('type', $type)
            ->whereDate('postdate', today())
            ->update(['no_rkm_medis' => $validated['no_rkm_medis']]);
        
        if ($result) {
            // Jika Apotek, mark as completed
            if ($type === 'Apotek') {
                AntrianLoket::where('type', $type)
                    ->where('no_rkm_medis', $validated['no_rkm_medis'])
                    ->whereDate('postdate', today())
                    ->update(['status' => '3']);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Nomor RM berhasil disimpan'
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Gagal menyimpan nomor RM'
        ], 400);
    }

    /**
     * Reset antrian untuk type tertentu
     */
    public function resetAntrian($type)
    {
        $type = $this->normalizeType($type);
        
        // Hapus semua antrian hari ini
        $deleted = AntrianLoket::where('type', $type)
            ->whereDate('postdate', today())
            ->delete();
        
        // Reset setting
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", 0);
        
        return response()->json([
            'status' => true,
            'message' => "Berhasil reset antrian {$type}"
        ]);
    }

    /**
     * Normalize type dari berbagai format input
     */
    private function normalizeType($input)
    {
        $clean = str_replace('panggil_', '', strtolower($input));
        
        return match($clean) {
            'loket' => 'Loket',
            'loket_vip' => 'LoketVIP',
            'cs' => 'CS',
            'cs_vip' => 'CSVIP',
            'apotek' => 'Apotek',
            default => 'Loket'
        };
    }
}