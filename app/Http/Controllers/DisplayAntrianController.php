<?php

namespace App\Http\Controllers;

use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use App\Helpers\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DisplayAntrianController extends Controller
{
    /**
     * Display antrian - router utama
     */
    public function index(Request $request)
    {
        $show = $request->get('show', 'all');
        
        $typeMap = [
            'loket' => 'Loket',
            'loket_vip' => 'LoketVIP',
            'cs' => 'CS',
            'cs_vip' => 'CSVIP',
            'apotek' => 'Apotek',
            'all' => null
        ];
        
        $type = $typeMap[$show] ?? null;
        $config = $type ? AntrianHelper::getByType($type) : null;
        
        // Common data
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $videoId = MliteSetting::getSetting('anjungan', 'vidio', '');
        $runningText = MliteSetting::getSetting('anjungan', 'text_loket', 'Selamat datang di sistem antrian kami');
        $namaInstansi = MliteSetting::getSetting('settings', 'nama_instansi', 'Rumah Sakit');
        $tanggal = $this->getTanggalIndonesia();
        
        if ($type) {
            // SINGLE DISPLAY - tampilkan 1 loket
            // return view('anjungan.display.single', [
            return view('anjungan.display.index', [
                'config' => $config,
                'logo' => $logo,
                'video_id' => $videoId,
                'running_text' => $runningText,
                'nama_instansi' => $namaInstansi,
                'tanggal' => $tanggal,
                'show' => $show
            ]);
        } else {
            // MULTI DISPLAY - tampilkan semua loket
            return view('anjungan.display.multi', [
                'logo' => $logo,
                'video_id' => $videoId,
                'running_text' => $runningText,
                'nama_instansi' => $namaInstansi,
                'tanggal' => $tanggal,
                'all_configs' => [
                    'Loket' => AntrianHelper::getByType('Loket'),
                    'LoketVIP' => AntrianHelper::getByType('LoketVIP'),
                    'CS' => AntrianHelper::getByType('CS'),
                    'CSVIP' => AntrianHelper::getByType('CSVIP'),
                    'Apotek' => AntrianHelper::getByType('Apotek'),
                ]
            ]);
        }
    }

    /**
     * ✅ FIXED: API Get antrian yang sedang dipanggil
     */
    public function getDisplay(Request $request)
    {
        $type = $request->get('type');
        
        if (!$type) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter type required'
            ]);
        }
        
        // Get field prefix untuk ambil setting
        $fieldPrefix = $this->getFieldPrefix($type);
        
        // Ambil nomor antrian terkini dari settings
        $nomorTerkini = (int) MliteSetting::getSetting('anjungan', "panggil_{$fieldPrefix}_nomor", 0);
        $loketTerkini = MliteSetting::getSetting('anjungan', "panggil_{$fieldPrefix}", '1');
        
        if ($nomorTerkini <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Belum ada antrian yang dipanggil'
            ]);
        }
        
        // Cek cache untuk prevent duplicate display
        $cacheKey = "display_shown_{$type}_{$nomorTerkini}";
        
        if (Cache::has($cacheKey)) {
            return response()->json([
                'status' => false,
                'message' => 'Antrian sudah ditampilkan'
            ]);
        }
        
        // Ambil data antrian dari database
        $antrian = AntrianLoket::where('type', $type)
            ->where('noantrian', $nomorTerkini)
            ->whereDate('postdate', today())
            ->first();
        
        if (!$antrian) {
            return response()->json([
                'status' => false,
                'message' => 'Data antrian tidak ditemukan'
            ]);
        }
        
        // Mark as shown in cache (expire after 10 seconds)
        Cache::put($cacheKey, true, now()->addSeconds(10));
        
        // Get config untuk prefix dan audio
        $config = AntrianHelper::getByType($type);
        
        return response()->json([
            'status' => true,
            'type' => $antrian->type,
            'prefix' => $config['prefix'] ?? 'A',
            'noantrian' => $antrian->noantrian,
            'loket' => $loketTerkini,
            'panggil' => $this->generateAudioSequence($type, $nomorTerkini, $loketTerkini),
            'id' => $antrian->kd
        ]);
    }

    /**
     * ✅ IMPROVED: Mark antrian sebagai selesai dipanggil
     */
    public function setDisplaySelesai(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Display acknowledged'
        ]);
    }

    /**
     * ✅ NEW: API Get statistik antrian
     */
    public function getStats(Request $request)
    {
        $type = $request->get('type');
        
        if (!$type) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter type required'
            ]);
        }
        
        $stats = [
            'total' => AntrianLoket::where('type', $type)
                ->whereDate('postdate', today())
                ->count(),
            
            'menunggu' => AntrianLoket::where('type', $type)
                ->where('status', '0')
                ->whereDate('postdate', today())
                ->count(),
            
            'diproses' => AntrianLoket::where('type', $type)
                ->where('status', '1')
                ->whereDate('postdate', today())
                ->count(),
            
            'selesai' => AntrianLoket::where('type', $type)
                ->where('status', '2')
                ->whereDate('postdate', today())
                ->count(),
        ];
        
        return response()->json([
            'status' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Generate audio sequence untuk announcement
     */
    private function generateAudioSequence($type, $noantrian, $loket)
    {
        $files = [];
        
        $files[] = 'antrian';
        
        $config = AntrianHelper::getByType($type);
        $prefix = strtolower($config['audio_name'] ?? $config['prefix']);
        $files[] = $prefix;
        
        $this->convertNumberToIndonesianAudio($noantrian, $files);
        
        $files[] = 'counter';
        
        $this->convertNumberToIndonesianAudio($loket, $files);
        
        return $files;
    }

    /**
     * Convert number ke audio files bahasa Indonesia
     */
    private function convertNumberToIndonesianAudio($number, &$files)
    {
        $num = (int)$number;
        
        if ($num == 0) {
            $files[] = 'nol';
            return;
        }
        
        if ($num >= 200) {
            $ratusan = intdiv($num, 100);
            $files[] = $this->digitToWord($ratusan);
            $files[] = 'ratus';
            $num = $num % 100;
        } elseif ($num >= 100) {
            $files[] = 'seratus';
            $num = $num % 100;
        }
        
        if ($num >= 20) {
            $puluhan = intdiv($num, 10);
            $files[] = $this->digitToWord($puluhan);
            $files[] = 'puluh';
            $num = $num % 10;
        } elseif ($num >= 11) {
            if ($num == 11) {
                $files[] = 'sebelas';
            } else {
                $satuan = $num - 10;
                $files[] = $this->digitToWord($satuan);
                $files[] = 'belas';
            }
            return;
        } elseif ($num == 10) {
            $files[] = 'sepuluh';
            return;
        }
        
        if ($num > 0) {
            $files[] = $this->digitToWord($num);
        }
    }

    /**
     * Convert digit ke kata Indonesia
     */
    private function digitToWord($digit)
    {
        $words = [
            0 => 'nol', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat',
            5 => 'lima', 6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan'
        ];
        
        return $words[$digit] ?? 'nol';
    }

    /**
     * Get field prefix untuk settings
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

    /**
     * Get tanggal dalam format Indonesia
     */
    private function getTanggalIndonesia()
    {
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => "Jum'at",
            'Saturday' => 'Sabtu'
        ];
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $now = now();
        $dayName = $days[$now->format('l')];
        $day = $now->day;
        $month = $months[$now->month];
        $year = $now->year;
        
        return "{$dayName}, {$day} {$month} {$year}";
    }
}