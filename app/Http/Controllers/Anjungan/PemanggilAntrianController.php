<?php

namespace App\Http\Controllers\Anjungan;

use App\Http\Controllers\Controller;
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
     * ✅ FIXED: Universal render logic
     */
    private function renderPanggil(Request $request, $type)
    {
        $config = AntrianHelper::getByType($type);
        
        if (!$config) {
            abort(404, 'Loket tidak ditemukan');
        }
        
        $currentLoket = $request->get('loket', '1');
        
        // Handle form lompat antrian
        if ($request->has('antrian') && $request->filled('antrian')) {
            $this->lompatAntrian($type, $request->antrian, $currentLoket);
            return redirect($request->url() . '?show=' . $config['show'] . '&loket=' . $currentLoket . '&skip_audio=1');
        }
        
        // ✅ Handle tombol "Berikutnya" (SILENT - tidak play audio)
        if ($request->get('action') === 'next') {
            Log::info('🔵 BERIKUTNYA (SILENT)', ['type' => $type]);
            
            $this->panggilAntrianBerikutnya($type, $currentLoket, true); // silent=true
            
            return redirect($request->url() . '?show=' . $config['show'] . '&loket=' . $currentLoket . '&skip_audio=1');
        }
        
        // Ambil setting loket dari database
        $fieldPrefix = $this->getFieldPrefix($type);
        $settingLoket = MliteSetting::getSetting('anjungan', "antrian_{$fieldPrefix}", '1,2,3');
        $loketList = array_filter(array_map('trim', explode(',', $settingLoket)));
        
        $nomorTerkini = $this->getNomorAntrianTerkini($type);
        $totalAntrian = AntrianLoket::getNomorTerakhir($type);
        $hitungAntrian = AntrianLoket::where('type', $type)->hariIni()->get();
        
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
     * ✅ Panggil antrian berikutnya dengan silent mode
     */
    private function panggilAntrianBerikutnya($type, $loket, $silent = true)
    {
        $nomorTerkini = $this->getNomorAntrianTerkini($type);
        $nomorBerikutnya = $nomorTerkini + 1;
        
        // ✅ Set flag play_audio
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "play_audio_{$fieldPrefix}", $silent ? '0' : '1');
        
        Log::info($silent ? '🔇 SILENT' : '🔊 WITH AUDIO', ['nomor' => $nomorBerikutnya]);
        
        // Update database
        AntrianLoket::where('type', $type)
            ->where('noantrian', $nomorBerikutnya)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1'
            ]);
        
        // Update settings
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $nomorBerikutnya);
    }

    /**
     * Lompat ke nomor antrian tertentu (ALWAYS SILENT - no audio)
     */
    private function lompatAntrian($type, $nomor, $loket, $silent = true)
    {
        if (!is_numeric($nomor) || $nomor < 1) return;
        
        $fieldPrefix = $this->getFieldPrefix($type);
        // ✅ ALWAYS SILENT untuk lompat (play_audio = 0)
        MliteSetting::setSetting('anjungan', "play_audio_{$fieldPrefix}", '0');
        
        Log::info('🔇 Lompat antrian (SILENT)', ['nomor' => $nomor]);
        
        AntrianLoket::where('type', $type)
            ->where('noantrian', $nomor)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1'
            ]);
        
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $nomor);
    }

    private function getNomorAntrianTerkini($type)
    {
        $fieldPrefix = $this->getFieldPrefix($type);
        return (int) MliteSetting::getSetting('anjungan', "panggil_{$fieldPrefix}_nomor", 0);
    }

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

    // ============= API =============

    /**
     * ✅ API Set panggil (SELALU dengan audio)
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
        
        Log::info('🔵 API setPanggil (WITH AUDIO)', ['type' => $type, 'nomor' => $noantrian]);
        
        // ✅ Set flag play_audio = 1 (PLAY AUDIO)
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "play_audio_{$fieldPrefix}", '1');
        
        // Update database
        AntrianLoket::where('type', $type)
            ->where('noantrian', $noantrian)
            ->whereDate('postdate', today())
            ->update([
                'status' => '1',
                'loket' => $loket,
                'end_time' => now()->format('H:i:s')
            ]);
        
        // Update settings
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", $noantrian);
        
        return response()->json(['status' => true, 'message' => 'Panggil berhasil']);
    }

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
            if ($type === 'Apotek') {
                AntrianLoket::where('type', $type)
                    ->where('no_rkm_medis', $validated['no_rkm_medis'])
                    ->whereDate('postdate', today())
                    ->update(['status' => '3']);
            }
            
            return response()->json(['status' => true, 'message' => 'Nomor RM berhasil disimpan']);
        }
        
        return response()->json(['status' => false, 'message' => 'Gagal menyimpan nomor RM'], 400);
    }

    public function resetAntrian($type)
    {
        $type = $this->normalizeType($type);
        
        AntrianLoket::where('type', $type)->whereDate('postdate', today())->delete();
        
        $fieldPrefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$fieldPrefix}_nomor", 0);
        
        return response()->json(['status' => true, 'message' => "Berhasil reset antrian {$type}"]);
    }

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