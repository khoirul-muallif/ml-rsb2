<?php

namespace App\Http\Controllers\Anjungan;

use App\Http\Controllers\Controller;
use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use App\Helpers\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AntrianLoketController extends Controller
{
    /**
     * Halaman anjungan pasien
     */
    public function index()
    {
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $namaInstansi = MliteSetting::getSetting('settings', 'nama_instansi', 'Rumah Sakit');
        $alamat = MliteSetting::getSetting('settings', 'alamat', '');
        $nomorTelepon = MliteSetting::getSetting('settings', 'nomor_telepon', ''); // ✅ TAMBAH
        $runningText = MliteSetting::getSetting('anjungan', 'text_anjungan', 'Selamat datang');
        
        $loketTypes = AntrianHelper::getAllSorted();
        
        $today = now()->toDateString();
        $summary = $this->getSummary($today);
        
        return view('anjungan.loket.index', [
            'logo' => $logo,
            'nama_instansi' => $namaInstansi,
            'alamat' => $alamat,
            'nomor_telepon' => $nomorTelepon, // ✅ TAMBAH
            'running_text' => $runningText,
            'loket_types' => $loketTypes,
            'summary' => $summary
        ]);
    }

    /**
     * Halaman menu utama - pilih grup layanan
     */
    public function menu()
    {
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $namaInstansi = MliteSetting::getSetting('settings', 'nama_instansi', 'Rumah Sakit');
        $alamat = MliteSetting::getSetting('settings', 'alamat', '');
        $nomorTelepon = MliteSetting::getSetting('settings', 'nomor_telepon', ''); // ✅ TAMBAH
        $runningText = MliteSetting::getSetting('anjungan', 'text_anjungan', 'Selamat datang');
        
        return view('anjungan.layanan.menu', [
            'logo' => $logo,
            'nama_instansi' => $namaInstansi,
            'alamat' => $alamat,
            'nomor_telepon' => $nomorTelepon, // ✅ TAMBAH
            'running_text' => $runningText
        ]);
    }

    /**
     * Helper: Show specific group
     */
    private function showGroup(array $types, string $view)
    {
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $namaInstansi = MliteSetting::getSetting('settings', 'nama_instansi', 'Rumah Sakit');
        $alamat = MliteSetting::getSetting('settings', 'alamat', '');
        $nomorTelepon = MliteSetting::getSetting('settings', 'nomor_telepon', ''); // ✅ TAMBAH
        $runningText = MliteSetting::getSetting('anjungan', 'text_anjungan', 'Selamat datang');
        
        $loketTypes = AntrianHelper::getAllSorted();
        
        $today = now()->toDateString();
        $summary = $this->getSummary($today);
        
        return view($view, [
            'logo' => $logo,
            'nama_instansi' => $namaInstansi,
            'alamat' => $alamat,
            'nomor_telepon' => $nomorTelepon, // ✅ TAMBAH
            'running_text' => $runningText,
            'loket_types' => $loketTypes,
            'summary' => $summary
        ]);
    }

    /**
     * Halaman Loket Pendaftaran (Loket & LoketVIP)
     */
    public function loket()
    {
        return $this->showGroup(['Loket', 'LoketVIP'], 'anjungan.layanan.loket');
    }

    /**
     * Halaman Customer Service (CS & CSVIP)
     */
    public function cs()
    {
        return $this->showGroup(['CS', 'CSVIP'], 'anjungan.layanan.cs');
    }

    /**
     * Halaman Apotek & Farmasi
     */
    public function apotek()
    {
        return $this->showGroup(['Apotek'], 'anjungan.layanan.apotek');
    }


    public function ambil(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:Loket,LoketVIP,CS,CSVIP,Apotek'
            ]);

            $type = $request->type;
            $today = now()->toDateString();

            $config = AntrianHelper::getByType($type);
            
            if (!$config) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tipe loket tidak valid'
                ], 400);
            }

            // ✅ DEBUG LOG
            $lastNumber = DB::table('ml_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $today)
                ->max('noantrian');

            $nextNumber = $lastNumber ? (int)$lastNumber + 1 : 1;
            
            // ✅ LOG BEFORE INSERT
            //Log::info("BEFORE INSERT - Type: $type, Last: $lastNumber, Next: $nextNumber");

            DB::beginTransaction();
            
            try {
                $id = DB::table('ml_antrian_loket')->insertGetId([
                    'type' => $type,
                    'noantrian' => $nextNumber,
                    'postdate' => $today,
                    'status' => '0',
                    'category' => $config['category'] ?? 'reguler',
                    'loket' => '0',
                    'start_time' => now()->format('H:i:s'),
                    'end_time' => '00:00:00',
                    'no_rkm_medis' => null
                ]);
                
                DB::commit();
                
                // ✅ LOG AFTER INSERT
                //Log::info("AFTER INSERT - ID: $id, Type: $type, Number: $nextNumber");
            } catch (\Exception $e) {
                DB::rollBack();
                //Log::error("INSERT FAILED - Type: $type, Error: " . $e->getMessage());
                throw $e;
            }

            $displayNumber = $config['prefix'] . $nextNumber;

            $response = [
                'status' => true,
                'id' => $id,
                'type' => $type,
                'prefix' => $config['prefix'],
                'nomor' => $nextNumber,
                'display' => $displayNumber,
                'label' => $config['full_label'],
                'color' => $config['color'],
                'category' => $config['category'] ?? 'reguler',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
            
            // ✅ LOG RESPONSE
            //Log::info("RESPONSE - " . json_encode($response));
            
            return response()->json($response);

        } catch (\Exception $e) {
            //Log::error("EXCEPTION - " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getSummary($date = null)
    {
        if (!$date) {
            $date = now()->toDateString();
        }

        $types = ['Loket', 'LoketVIP', 'CS', 'CSVIP', 'Apotek'];
        $summary = [];
        
        foreach ($types as $type) {
            $config = AntrianHelper::getByType($type);
            
            if (!$config) continue;
            
            $total = DB::table('ml_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $date)
                ->count();
            
            $menunggu = DB::table('ml_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $date)
                ->where('status', '0')
                ->count();
            
            $lastNumber = DB::table('ml_antrian_loket')
                ->where('type', $type)
                ->where('postdate', $date)
                ->max('noantrian');
            
            // ✅ CAST TO INT
            $lastNumber = $lastNumber ? (int)$lastNumber : 0;
            
            $summary[$type] = [
                'label' => $config['label'],
                'prefix' => $config['prefix'],
                'total' => $total,
                'menunggu' => $menunggu,
                'last_number' => $lastNumber
            ];
            
            // ✅ LOG SUMMARY
            //Log::info("SUMMARY - Type: $type, Total: $total, Last: $lastNumber");
        }
        
        return $summary;
    }

    public function summary()
    {
        try {
            $today = now()->toDateString();
            $summary = $this->getSummary($today);
            
            return response()->json([
                'status' => true,
                'date' => $today,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            //Log::error("SUMMARY ERROR - " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * API: Get info antrian by ID
     */
    public function info($id)
    {
        try {
            $antrian = DB::table('ml_antrian_loket')
                ->where('kd', $id)
                ->first();

            if (!$antrian) {
                return response()->json([
                    'status' => false,
                    'message' => 'Antrian tidak ditemukan'
                ], 404);
            }

            $config = AntrianHelper::getByType($antrian->type);
            $displayNumber = $config['prefix'] . $antrian->noantrian;

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

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Cek nomor antrian
     */
    public function cek(Request $request)
    {
        try {
            $request->validate([
                'display' => 'required|string'
            ]);

            $display = strtoupper($request->display);
            $today = now()->toDateString();
            
            preg_match('/^([A-Z]+)(\d+)$/', $display, $matches);
            
            if (count($matches) !== 3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Format nomor tidak valid'
                ], 400);
            }
            
            $prefix = $matches[1];
            $nomor = (int)$matches[2];
            
            $config = AntrianHelper::getByPrefix($prefix);
            
            if (!$config) {
                return response()->json([
                    'status' => false,
                    'message' => 'Prefix tidak dikenali'
                ], 400);
            }
            
            $antrian = DB::table('ml_antrian_loket')
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
            
            $posisi = DB::table('ml_antrian_loket')
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

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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