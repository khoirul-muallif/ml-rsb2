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
        $loket = [];
        $namaloket = '';
        $panggil_loket = '';
        $antrian = 0;
        $noantrian = 0;
        $xcounter = [];
        
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
        $type = 'Loket';
        $prefix = 'A';
        
        // Ambil setting loket
        $setting_loket = MliteSetting::getSetting('anjungan', 'antrian_loket', '1,2,3');
        $loket = explode(',', $setting_loket);
        
        // Proses jika ada parameter loket (panggil antrian berikutnya)
        if ($request->has('loket')) {
            $this->panggilAntrianBerikutnya($type, $request->loket);
        }
        
        // Proses jika ada parameter antrian (lompat ke nomor tertentu)
        if ($request->has('antrian') && $request->has('reset')) {
            $this->lompatAntrian($type, $request->antrian, $request->reset);
        }
        
        // Ambil nomor antrian yang sedang dipanggil
        $tcounter = MliteSetting::getSetting('anjungan', 'panggil_loket_nomor', 0);
        
        // Ambil total antrian yang sudah diambil hari ini
        $noantrian = AntrianLoket::getNomorTerakhir($type);
        
        // Hitung jumlah antrian hari ini
        $hitung_antrian = AntrianLoket::where('type', $type)
            ->hariIni()
            ->get();
        
        // Generate audio files untuk nomor antrian
        $xcounter = $this->generateAudioTags($tcounter, $prefix, $request->loket);
        
        return view('anjungan.pemanggil.loket', [
            'title' => 'Pemanggil Loket',
            'show' => 'panggil_loket',
            'loket' => $loket,
            'namaloket' => strtolower($prefix),
            'panggil_loket' => 'panggil_loket',
            'antrian' => $tcounter,
            'noantrian' => $noantrian,
            'hitung_antrian' => $hitung_antrian,
            'xcounter' => $xcounter,
        ]);
    }

    /**
     * Handle pemanggil CS (Type: CS, Prefix: B)
     */
    private function handlePanggilCS(Request $request)
    {
        $type = 'CS';
        $prefix = 'B';
        
        $setting_loket = MliteSetting::getSetting('anjungan', 'antrian_cs', '1,2,3');
        $loket = explode(',', $setting_loket);
        
        if ($request->has('loket')) {
            $this->panggilAntrianBerikutnya($type, $request->loket);
        }
        
        if ($request->has('antrian') && $request->has('reset')) {
            $this->lompatAntrian($type, $request->antrian, $request->reset);
        }
        
        $tcounter = MliteSetting::getSetting('anjungan', 'panggil_cs_nomor', 0);
        $noantrian = AntrianLoket::getNomorTerakhir($type);
        
        $hitung_antrian = AntrianLoket::where('type', $type)
            ->hariIni()
            ->get();
        
        $xcounter = $this->generateAudioTags($tcounter, $prefix, $request->loket);
        
        return view('anjungan.pemanggil.loket', [
            'title' => 'Pemanggil CS',
            'show' => 'panggil_cs',
            'loket' => $loket,
            'namaloket' => strtolower($prefix),
            'panggil_loket' => 'panggil_cs',
            'antrian' => $tcounter,
            'noantrian' => $noantrian,
            'hitung_antrian' => $hitung_antrian,
            'xcounter' => $xcounter,
        ]);
    }

    /**
     * Handle pemanggil apotek (Type: Apotek, Prefix: F)
     */
    private function handlePanggilApotek(Request $request)
    {
        $type = 'Apotek';
        $prefix = 'F';
        
        $setting_loket = MliteSetting::getSetting('anjungan', 'antrian_apotek', '1,2');
        $loket = explode(',', $setting_loket);
        
        if ($request->has('loket')) {
            $this->panggilAntrianBerikutnya($type, $request->loket);
        }
        
        if ($request->has('antrian') && $request->has('reset')) {
            $this->lompatAntrian($type, $request->antrian, $request->reset);
        }
        
        $tcounter = MliteSetting::getSetting('anjungan', 'panggil_apotek_nomor', 0);
        $noantrian = AntrianLoket::getNomorTerakhir($type);
        
        $hitung_antrian = AntrianLoket::where('type', $type)
            ->hariIni()
            ->get();
        
        $xcounter = $this->generateAudioTags($tcounter, $prefix, $request->loket);
        
        return view('anjungan.pemanggil.loket', [
            'title' => 'Pemanggil Apotek',
            'show' => 'panggil_apotek',
            'loket' => $loket,
            'namaloket' => strtolower($prefix),
            'panggil_loket' => 'panggil_apotek',
            'antrian' => $tcounter,
            'noantrian' => $noantrian,
            'hitung_antrian' => $hitung_antrian,
            'xcounter' => $xcounter,
        ]);
    }

    /**
     * Panggil antrian berikutnya
     */
    private function panggilAntrianBerikutnya($type, $loket)
    {
        $field_prefix = strtolower($type);
        if ($type === 'CS') {
            $field_prefix = 'cs';
        } elseif ($type === 'Loket') {
            $field_prefix = 'loket';
        } elseif ($type === 'Apotek') {
            $field_prefix = 'apotek';
        }
        
        $tcounter = MliteSetting::getSetting('anjungan', "panggil_{$field_prefix}_nomor", 0);
        $next_counter = $tcounter + 1;
        
        // Update antrian yang dipanggil
        AntrianLoket::where('type', $type)
            ->where('noantrian', $next_counter)
            ->whereDate('postdate', today())
            ->update([
                'end_time' => now()->format('H:i:s'),
                'loket' => $loket,
                'status' => '1'
            ]);
        
        // Update setting
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", $next_counter);
    }

    /**
     * Lompat ke nomor antrian tertentu
     */
    private function lompatAntrian($type, $antrian, $loket)
    {
        $field_prefix = strtolower($type);
        if ($type === 'CS') {
            $field_prefix = 'cs';
        } elseif ($type === 'Loket') {
            $field_prefix = 'loket';
        } elseif ($type === 'Apotek') {
            $field_prefix = 'apotek';
        }
        
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}", $loket);
        MliteSetting::setSetting('anjungan', "panggil_{$field_prefix}_nomor", $antrian);
    }

    /**
     * Generate audio tags untuk pemanggilan
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
        
        // Audio huruf prefix (A, B, F, dll)
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
     * API: Set panggil (dipanggil via AJAX)
     */
    public function setPanggil(Request $request)
    {
        $type = $this->getTypeFromString($request->type);
        $noantrian = $request->noantrian;
        $loket = $request->loket;
        
        $result = AntrianLoket::where('type', $type)
            ->where('noantrian', $noantrian)
            ->whereDate('postdate', today())
            ->update([
                'status' => '1',
                'loket' => $loket
            ]);
        
        if ($result) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update'
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Gagal update'
        ], 400);
    }

    /**
     * API: Simpan nomor rekam medis
     */
    public function simpanNoRM(Request $request)
    {
        $request->validate([
            'noantrian' => 'required',
            'type' => 'required',
            'no_rkm_medis' => 'required|size:6'
        ]);
        
        $type = $this->getTypeFromString($request->type);
        
        $result = AntrianLoket::where('noantrian', $request->noantrian)
            ->where('type', $type)
            ->whereDate('postdate', today())
            ->update(['no_rkm_medis' => $request->no_rkm_medis]);
        
        if ($result) {
            // Jika type Apotek, update status ke selesai
            if ($type === 'Apotek') {
                AntrianLoket::where('type', $type)
                    ->whereDate('postdate', today())
                    ->where('no_rkm_medis', $request->no_rkm_medis)
                    ->update(['status' => '3']);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Simpan nomor RM berhasil!!'
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Simpan nomor RM gagal!!'
        ], 400);
    }

    /**
     * Helper: Convert string type ke Type yang benar
     */
    private function getTypeFromString($typeString)
    {
        $types = [
            'loket' => 'Loket',
            'cs' => 'CS',
            'apotek' => 'Apotek',
            'igd' => 'IGD'
        ];
        
        return $types[strtolower($typeString)] ?? 'Loket';
    }

    /**
     * Reset antrian untuk hari tertentu
     */
    public function resetAntrian($type)
    {
        $type = ucfirst($type);
        
        $deleted = AntrianLoket::where('type', $type)
            ->whereDate('postdate', today())
            ->delete();
        
        if ($deleted) {
            // Buat antrian awal
            AntrianLoket::create([
                'type' => $type,
                'noantrian' => '1',
                'postdate' => today(),
                'start_time' => now()->format('H:i:s')
            ]);
            
            return response()->json([
                'status' => true,
                'message' => "Berhasil reset antrian {$type}"
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => "Antrian {$type} tidak ada data"
        ], 404);
    }
}