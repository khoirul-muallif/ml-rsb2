<?php

namespace App\Http\Controllers;

use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use App\Helpers\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianLoketController extends Controller
{
    /**
     * Halaman anjungan pasien (untuk ambil nomor)
     */
    public function index()
    {
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $namaInstansi = MliteSetting::getSetting('settings', 'nama_instansi', 'Rumah Sakit');
        $alamat = MliteSetting::getSetting('settings', 'alamat', '');
        $runningText = MliteSetting::getSetting('anjungan', 'text_anjungan', 'Selamat datang');
        
        // Get all available loket types dari config
        $loketTypes = AntrianHelper::getAllSorted();
        
        return view('anjungan.loket.index', [
            'logo' => $logo,
            'nama_instansi' => $namaInstansi,
            'alamat' => $alamat,
            'running_text' => $runningText,
            'loket_types' => $loketTypes
        ]);
    }

    /**
     * API: Ambil nomor antrian baru
     * 
     * POST /anjungan/loket/ambil
     * Body: { type: 'Loket' | 'LoketVIP' | 'CS' | 'CSVIP' | 'Apotek' }
     */
    public function ambil(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:Loket,LoketVIP,CS,CSVIP,Apotek'
        ]);

        $type = $request->type;
        $today = now()->toDateString();

        // Get config dari helper
        $config = AntrianHelper::getByType($type);
        
        if (!$config) {
            return response()->json([
                'status' => false,
                'message' => 'Tipe loket tidak valid'
            ], 400);
        }

        // Get nomor terakhir untuk type ini hari ini
        $lastNumber = DB::table('mlite_antrian_loket')
            ->where('type', $type)
            ->where('postdate', $today)
            ->orderByDesc('noantrian')
            ->value('noantrian');

        $nextNumber = $lastNumber ? (int)$lastNumber + 1 : 1;

        // Insert antrian baru
        $id = DB::table('mlite_antrian_loket')->insertGetId([
            'type' => $type,
            'noantrian' => $nextNumber,
            'postdate' => $today,
            'status' => '0', // Menunggu
            'category' => $config['category'],
            'loket' => '0', // Belum ada loket
            'start_time' => now()->format('H:i:s'),
            'end_time' => '00:00:00',
            'no_rkm_medis' => null
        ]);

        // Format display number dengan prefix
        $displayNumber = $config['prefix'] . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => true,
            'id' => $id,
            'type' => $type,
            'prefix' => $config['prefix'],
            'nomor' => $nextNumber,
            'display' => $displayNumber,
            'label' => $config['full_label'],
            'color' => $config['color'],
            'category' => $config['category'],
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * API: Get info antrian by ID (untuk print ulang)
     * 
     * GET /anjungan/loket/info/{id}
     */
    public function info($id)
    {
        $antrian = DB::table('mlite_antrian_loket')
            ->where('kd', $id)
            ->first();

        if (!$antrian) {
            return response()->json([
                'status' => false,
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        $config = AntrianHelper::getByType($antrian->type);
        $displayNumber = $config['prefix'] . str_pad($antrian->noantrian, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => true,
            'id' => $antrian->kd,
            'type' => $antrian->type,
            'prefix' => $config['prefix'],
            'nomor' => $antrian->noantrian,
            'display' => $displayNumber,
            'label' => $config['full_label'],
            'status_antrian' => $antrian->status,
            'loket' => $antrian->loket,
            'timestamp' => $antrian->postdate . ' ' . $antrian->start_time
        ]);
    }

    /**
     * API: Get summary antrian hari ini
     * 
     * GET /anjungan/loket/summary
     */
    public function summary()
    {
        $today = now()->toDateString();
        $types = ['Loket', 'LoketVIP', 'CS', 'CSVIP', 'Apotek'];
        
        $summary = [];
        
        foreach ($types as $type) {
            $config = AntrianHelper::getByType($type);
            
            $total = DB::table('mlite_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $today)
                ->count();
            
            $menunggu = DB::table('mlite_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $today)
                ->where('status', '0')
                ->count();
            
            $summary[$type] = [
                'label' => $config['label'],
                'prefix' => $config['prefix'],
                'total' => $total,
                'menunggu' => $menunggu,
                'last_number' => DB::table('mlite_antrian_loket')
                    ->where('type', $type)
                    ->where('postdate', $today)
                    ->orderByDesc('noantrian')
                    ->value('noantrian') ?? 0
            ];
        }
        
        return response()->json([
            'status' => true,
            'date' => $today,
            'summary' => $summary
        ]);
    }

    /**
     * API: Cek nomor antrian (untuk pasien cek posisi)
     * 
     * GET /anjungan/loket/cek?display=A001
     */
    public function cek(Request $request)
    {
        $request->validate([
            'display' => 'required|string'
        ]);

        $display = strtoupper($request->display);
        $today = now()->toDateString();
        
        // Parse prefix dan nomor
        preg_match('/^([A-Z]+)(\d+)$/', $display, $matches);
        
        if (count($matches) !== 3) {
            return response()->json([
                'status' => false,
                'message' => 'Format nomor tidak valid'
            ], 400);
        }
        
        $prefix = $matches[1];
        $nomor = (int)$matches[2];
        
        // Find type by prefix
        $config = AntrianHelper::getByPrefix($prefix);
        
        if (!$config) {
            return response()->json([
                'status' => false,
                'message' => 'Prefix tidak dikenali'
            ], 400);
        }
        
        // Get antrian
        $antrian = DB::table('mlite_antrian_loket')
            ->where('type', $config['type'])
            ->where('noantrian', $nomor)
            ->where('postdate', $today)
            ->first();
        
        if (!$antrian) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor antrian tidak ditemukan'
            ], 404);
        }
        
        // Hitung posisi
        $posisi = DB::table('mlite_antrian_loket')
            ->where('type', $config['type'])
            ->where('postdate', $today)
            ->where('noantrian', '<', $nomor)
            ->where('status', '0')
            ->count() + 1;
        
        return response()->json([
            'status' => true,
            'nomor' => $display,
            'label' => $config['full_label'],
            'status_antrian' => $antrian->status,
            'status_label' => $this->getStatusLabel($antrian->status),
            'posisi' => $posisi,
            'loket' => $antrian->loket > 0 ? $antrian->loket : null
        ]);
    }

    /**
     * Helper: Get status label
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            '0' => 'Menunggu',
            '1' => 'Sedang Dipanggil',
            '2' => 'Selesai',
            '3' => 'Tidak Hadir',
            default => 'Unknown'
        };
    }
}