<?php

namespace App\Http\Controllers;

use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemanggilAntrianController extends Controller
{
    /**
     * Tampilkan halaman pemanggil antrian
     */
    public function index(Request $request)
    {
        $show = $request->get('show');
        
        switch ($show) {
            case 'panggil_loket':
                return $this->handlePanggilLoket($request);
            
            case 'panggil_cs':
                return $this->handlePanggilCS($request);
            
            case 'panggil_apotek':
                return $this->handlePanggilApotek($request);
            
            default:
                return view('anjungan.pemanggil.index');
        }
    }

    /**
     * Handle pemanggil loket (Type: Loket, Prefix: A)
     */
    private function handlePanggilLoket(Request $request)
    {
        return $this->renderPanggil($request, 'Loket', 'A', 'panggil_loket', 'Pemanggil Loket');
    }

    /**
     * Handle pemanggil CS (Type: CS, Prefix: B)
     */
    private function handlePanggilCS(Request $request)
    {
        return $this->renderPanggil($request, 'CS', 'B', 'panggil_cs', 'Pemanggil CS');
    }

    /**
     * Handle pemanggil apotek (Type: Apotek, Prefix: F)
     */
    private function handlePanggilApotek(Request $request)
    {
        return $this->renderPanggil($request, 'Apotek', 'F', 'panggil_apotek', 'Pemanggil Apotek');
    }

    /**
     * Main render logic untuk semua jenis loket
     */
    private function renderPanggil(Request $request, $type, $prefix, $panggil_loket, $title)
    {
        // ========== HANDLE FORM LOMPAT ANTRIAN ==========
        if ($request->has('antrian') && $request->filled('antrian')) {
            $this->lompatAntrian($type, $request->antrian, $request->get('loket', '1'));
        }
        
        // ========== HANDLE TOMBOL FORWARD (NEXT) ==========
        if ($request->has('loket') && !$request->has('antrian')) {
            $this->panggilAntrianBerikutnya($type, $request->loket);
        }
        
        // Ambil setting loket dari database
        $setting_key = match($type) {
            'Loket' => 'antrian_loket',
            'CS' => 'antrian_cs',
            'Apotek' => 'antrian_apotek',
        };
        
        $setting_loket = MliteSetting::getSetting('anjungan', $setting_key, '1,2,3');
        $loket = array_filter(array_map('trim', explode(',', $setting_loket)));
        
        // Ambil nomor antrian yang sedang dipanggil
        $tcounter = $this->getNomorAntrianTerkini($type);
        
        // Ambil total antrian yang sudah diambil hari ini
        $noantrian = AntrianLoket::getNomorTerakhir($type);
        
        // Hitung jumlah antrian hari ini
        $hitung_antrian = AntrianLoket::where('type', $type)
            ->hariIni()
            ->get();
        
        // Generate audio files
        $xcounter = $this->generateAudioTags($tcounter, $prefix, $request->get('loket'));
        
        // Tentukan view berdasarkan type
        $view = match($type) {
            'Loket' => 'anjungan.pemanggil.loket',
            'CS' => 'anjungan.pemanggil.cs',
            'Apotek' => 'anjungan.pemanggil.apotek',
        };
        
        return view($view, [
            'title' => $title,
            'show' => $panggil_loket,
            'loket' => $loket,
            'namaloket' => strtolower($prefix),
            'panggil_loket' => $panggil_loket,
            'antrian' => $tcounter,
            'noantrian' => $noantrian,
            'hitung_antrian' => $hitung_antrian,
            'xcounter' => $xcounter,
        ]);
    }

    /**
     * HANDLER: Panggil antrian berikutnya
     * Triggered: Klik tombol forward (→)
     */
    private function panggilAntrianBerikutnya($type, $loket)
    {
        // Ambil nomor antrian saat ini
        $tcounter = $this->getNomorAntrianTerkini($type);
        $next_counter = $tcounter + 1;
        
        // Update data antrian di DB
        AntrianLoket::where('type', $type)
            ->where('noantrian', $next_counter)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1' // Status dipanggil
            ]);
        
        // Update setting nomor yang sedang dipanggil
        $field_prefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", $next_counter);
    }

    /**
     * HANDLER: Lompat ke nomor antrian tertentu
     * Triggered: Submit form input nomor antrian
     */
    private function lompatAntrian($type, $antrian, $loket)
    {
        $field_prefix = $this->getFieldPrefix($type);
        
        // Validasi input
        if (!is_numeric($antrian) || $antrian < 1) {
            return; // Skip jika invalid
        }
        
        // Update setting ke nomor yang diminta
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", $antrian);
        
        // Optional: Bisa log activity ini untuk audit trail
        //\Log::info("Antrian lompat: Type=$type, Nomor=$antrian, Loket=$loket");
    }

    /**
     * HELPER: Ambil nomor antrian terkini
     */
    private function getNomorAntrianTerkini($type)
    {
        $field_prefix = $this->getFieldPrefix($type);
        return (int) MliteSetting::getSetting('anjungan', "panggil_{$field_prefix}_nomor", 0);
    }

    /**
     * HELPER: Convert type ke field prefix
     * Contoh: 'Loket' -> 'loket', 'CS' -> 'cs'
     */
    private function getFieldPrefix($type)
    {
        return match(strtolower($type)) {
            'loket' => 'loket',
            'cs' => 'cs',
            'apotek' => 'apotek',
            default => 'loket'
        };
    }

    /**
     * Generate audio tags untuk preload
     */
    private function generateAudioTags($nomor, $prefix, $loket = null)
    {
        $tags = [];
        $base_url = asset('plugins/anjungan/suara');
        
        // Audio untuk setiap digit nomor antrian
        $digits = str_split((string)$nomor);
        foreach ($digits as $i => $digit) {
            $tags[] = '<audio id="suarabel' . $i . '" src="' . $base_url . '/' . $digit . '.wav" preload="auto"></audio>';
        }
        
        // Audio dasar
        $tags[] = '<audio id="suara_antrian" src="' . $base_url . '/antrian.wav" preload="auto"></audio>';
        $tags[] = '<audio id="suara_loket" src="' . $base_url . '/counter.wav" preload="auto"></audio>';
        
        // Audio huruf prefix
        $tags[] = '<audio id="suara_' . strtolower($prefix) . '" src="' . $base_url . '/' . strtolower($prefix) . '.wav" preload="auto"></audio>';
        
        // Audio untuk digit loket jika ada
        if ($loket) {
            $loket_digits = str_split((string)$loket);
            foreach ($loket_digits as $idx => $digit) {
                $tags[] = '<audio id="suaraloket' . $idx . '" src="' . $base_url . '/' . $digit . '.wav" preload="auto"></audio>';
            }
        }
        
        return $tags;
    }

    /**
     * API: Set panggil (dipanggil via AJAX dari JS)
     * Endpoint: POST /anjungan/api/setpanggil
     */
    public function setPanggil(Request $request)
    {
        $validated = $request->validate([
            'noantrian' => 'required|numeric',
            'type' => 'required|string',
            'loket' => 'nullable|string',
        ]);
        
        $type = $this->convertStringToType($validated['type']);
        $noantrian = $validated['noantrian'];
        $loket = $validated['loket'] ?? '1';
        
        // Update status antrian jadi "dipanggil"
        $result = AntrianLoket::where('type', $type)
            ->where('noantrian', $noantrian)
            ->whereDate('postdate', today())
            ->update([
                'status' => '1',
                'loket' => $loket,
                'end_time' => now()->format('H:i:s')
            ]);
        
        // Update setting untuk loket & nomor
        $field_prefix = $this->getFieldPrefix($type);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", $noantrian);
        
        return response()->json([
            'status' => (bool)$result,
            'message' => $result ? 'Panggil berhasil' : 'Data tidak ditemukan'
        ]);
    }

    /**
     * API: Simpan nomor rekam medis
     * Endpoint: POST /anjungan/api/simpannorm
     */
    public function simpanNoRM(Request $request)
    {
        $validated = $request->validate([
            'noantrian' => 'required|numeric',
            'type' => 'required|string',
            'no_rkm_medis' => 'required|size:6|numeric'
        ]);
        
        $type = $this->convertStringToType($validated['type']);
        
        // Update nomor RM ke database
        $result = AntrianLoket::where('noantrian', $validated['noantrian'])
            ->where('type', $type)
            ->whereDate('postdate', today())
            ->update(['no_rkm_medis' => $validated['no_rkm_medis']]);
        
        if ($result) {
            // Jika type Apotek, mark as completed
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
     * HELPER: Convert string type ke Type format DB
     */
    private function convertStringToType($typeString)
    {
        return match(strtolower($typeString)) {
            'loket' => 'Loket',
            'cs' => 'CS',
            'apotek' => 'Apotek',
            'igd' => 'IGD',
            default => 'Loket'
        };
    }

    /**
     * Reset antrian untuk tipe tertentu
     */
    public function resetAntrian($type)
    {
        $type = $this->convertStringToType($type);
        
        // Hapus semua antrian hari ini
        $deleted = AntrianLoket::where('type', $type)
            ->whereDate('postdate', today())
            ->delete();
        
        if ($deleted >= 0) {
            // Buat nomor awal
            AntrianLoket::create([
                'type' => $type,
                'noantrian' => '1',
                'postdate' => today(),
                'start_time' => now()->format('H:i:s')
            ]);
            
            // Reset setting
            $field_prefix = $this->getFieldPrefix($type);
            MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", 0);
            
            return response()->json([
                'status' => true,
                'message' => "Berhasil reset antrian {$type}"
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => "Gagal reset antrian {$type}"
        ], 400);
    }
}