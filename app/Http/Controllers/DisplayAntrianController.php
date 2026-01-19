<?php

namespace App\Http\Controllers;

use App\Models\AntrianLoket;
use App\Models\MliteSetting;
use Illuminate\Http\Request;

class DisplayAntrianController extends Controller
{
    /**
     * Tampilkan display antrian loket (untuk TV/Monitor)
     * Endpoint: GET /anjungan/display
     */
    public function index()
    {
        $title = 'D';
        $logo = MliteSetting::getSetting('settings', 'logo', 'logo.png');
        $video_id = MliteSetting::getSetting('anjungan', 'vidio', '');
        $running_text = MliteSetting::getSetting('anjungan', 'text_loket', 'Selamat datang di sistem antrian kami');
        
        // Info user (jika login)
        // $username = auth()->check() 
        //     ? auth()->user()->name 
        //     : 'Tamu';
        
        // Tanggal hari ini
        $tanggal = $this->getDayIndonesia(date('Y-m-d')) . ', ' . $this->getDateIndonesia(date('Y-m-d'));
        
        return view('anjungan.display.index', [
            'title' => $title,
            'logo' => $logo,
            'video_id' => $video_id,
            'running_text' => $running_text,
            //'username' => $username,
            'tanggal' => $tanggal,
        ]);
    }

    /**
     * API: Get antrian yang sedang dipanggil
     * Endpoint: GET /anjungan/api/getdisplay
     * Return: JSON dengan data antrian terbaru yang dipanggil
     */
    public function getDisplay(Request $request)
    {
        // Ambil antrian terbaru yang statusnya "dipanggil" (status = 1)
        $antrian = AntrianLoket::getTerbaruDipanggil();
        
        if (!$antrian) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada antrian yang dipanggil'
            ]);
        }
        
        // Ambil loket yang sedang aktif untuk type ini
        $loket = MliteSetting::getSetting('anjungan', "panggil_{$this->getFieldPrefix($antrian->type)}", '1');
        
        return response()->json([
            'status' => true,
            'type' => $antrian->type,
            'noantrian' => $antrian->noantrian,
            'loket' => $loket,
            'panggil' => $this->generateAudioFilesIndonesian($antrian->noantrian, $antrian->type, $loket),
            'id' => $antrian->kd
        ]);
    }

    /**
     * API: Mark antrian sebagai selesai
     * Endpoint: POST /anjungan/api/setdisplayselesai
     */
    public function setDisplaySelesai(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        
        $updated = AntrianLoket::where('kd', $request->id)
            ->update([
                'status' => '2', // Status: Selesai
                'end_time' => now()->format('H:i:s')
            ]);
        
        return response()->json([
            'status' => (bool)$updated,
            'message' => $updated ? 'Status updated' : 'Gagal update status'
        ]);
    }

    /**
     * Generate audio files sequence dengan Bahasa Indonesia
     * Contoh: nomor 125 → ['antrian', 'a', 'seratus', 'dua', 'puluh', 'lima', 'counter', '1']
     * 
     * @param int $noantrian
     * @param string $type (Loket, CS, Apotek)
     * @param int $loket
     * @return array
     */
    private function generateAudioFilesIndonesian($noantrian, $type, $loket)
    {
        $files = [];
        
        // 1. Suara pembuka "antrian"
        $files[] = 'antrian';
        
        // 2. Huruf prefix (A, B, F)
        $prefix = match($type) {
            'Loket' => 'a',
            'CS' => 'b',
            'Apotek' => 'f',
            default => 'a'
        };
        $files[] = $prefix;
        
        // 3. Nomor antrian dalam bahasa Indonesia
        $this->convertToIndonesianAudio($noantrian, $files);
        
        // 4. Suara "counter"
        $files[] = 'counter';
        
        // 5. Nomor loket dalam bahasa Indonesia
        $this->convertToIndonesianAudio($loket, $files);
        
        return $files;
    }

    /**
     * Convert nomor ke audio files bahasa Indonesia
     * Contoh: 125 → ['seratus', 'dua', 'puluh', 'lima']
     * 
     * @param int $number
     * @param array &$files (pass by reference)
     */
    private function convertToIndonesianAudio($number, &$files)
    {
        $num = (int)$number;
        
        if ($num == 0) {
            $files[] = 'nol';
            return;
        }
        
        // Ratusan
        if ($num >= 200) {
            $ratusan = intdiv($num, 100);
            $files[] = (string)$ratusan;
            $files[] = 'ratus';
            $num = $num % 100;
        } elseif ($num >= 100) {
            $files[] = 'seratus';
            $num = $num % 100;
        }
        
        // Puluhan
        if ($num >= 20) {
            $puluhan = intdiv($num, 10);
            $files[] = (string)$puluhan;
            $files[] = 'puluh';
            $num = $num % 10;
        } elseif ($num >= 11) {
            if ($num == 11) {
                $files[] = 'sebelas';
            } else {
                $satuan = $num - 10;
                $files[] = (string)$satuan;
                $files[] = 'belas';
            }
            return;
        } elseif ($num == 10) {
            $files[] = 'sepuluh';
            return;
        }
        
        // Satuan (1-9)
        if ($num > 0) {
            $files[] = (string)$num;
        }
    }

    /**
     * Generate audio files sequence (simple version - digit per digit)
     * Fallback jika ingin digit per digit: ['antrian', 'a', '1', '2', '5', 'counter', '1']
     * 
     * Tidak digunakan, hanya untuk reference
     */
    private function generateAudioFilesSimple($noantrian, $type, $loket)
    {
        $files = [];
        
        $files[] = 'antrian';
        
        $prefix = match($type) {
            'Loket' => 'a',
            'CS' => 'b',
            'Apotek' => 'f',
            default => 'a'
        };
        $files[] = $prefix;
        
        // Nomor antrian (digit per digit)
        $digits = str_split((string)$noantrian);
        foreach ($digits as $digit) {
            $files[] = $digit;
        }
        
        $files[] = 'counter';
        
        // Nomor loket (digit per digit)
        $loket_digits = str_split((string)$loket);
        foreach ($loket_digits as $digit) {
            $files[] = $digit;
        }
        
        return $files;
    }

    /**
     * Helper: Get field prefix berdasarkan type
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
     * Helper: Get hari dalam bahasa Indonesia
     */
    private function getDayIndonesia($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => "Jum'at",
            'Saturday' => 'Sabtu'
        ];
        
        return $days[\Carbon\Carbon::parse($date)->format('l')];
    }

    /**
     * Helper: Get tanggal dalam format Indonesia
     */
    private function getDateIndonesia($date)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        
        $carbon = \Carbon\Carbon::parse($date);
        $month = $months[$carbon->format('F')];
        
        return $carbon->format('d') . ' ' . $month . ' ' . $carbon->format('Y');
    }
}