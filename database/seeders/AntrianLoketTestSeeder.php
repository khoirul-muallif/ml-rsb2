<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrianLoketTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Fokus pada angka-angka yang rawan bug:
     * - Ribuan dengan ratusan (1100-1999)
     * - Angka dengan banyak "0" (1000, 1010, 1100, 2020)
     * - Angka dengan "11" di posisi berbeda (11, 111, 1011, 1111, 2111)
     * - Kombinasi kompleks yang sering error
     */
    public function run(): void
    {
        $today = Carbon::today();
        $baseTime = Carbon::createFromTime(8, 0, 0);
        
        // =============================================
        // LOKET REGULER - Bug-Prone Numbers
        // =============================================
        $loketBugNumbers = [
            // Edge Cases dengan "0"
            10,    // sepuluh
            100,   // seratus
            1000,  // seribu
            1001,  // seribu satu
            1010,  // seribu sepuluh (double "10")
            1100,  // seribu seratus (sering error: "nol ratus")
            2000,  // dua ribu
            2020,  // dua ribu dua puluh
            3003,  // tiga ribu tiga
            5050,  // lima ribu lima puluh
            
            // Cases dengan "11" di berbagai posisi
            11,    // sebelas
            111,   // seratus sebelas
            211,   // dua ratus sebelas
            1011,  // seribu sebelas
            1111,  // seribu seratus sebelas (MOST COMMON BUG!)
            1211,  // seribu dua ratus sebelas
            2111,  // dua ribu seratus sebelas
            
            // Kombinasi ratusan + belasan (sering error)
            1110,  // seribu seratus sepuluh (sering jadi "nol ratus sepuluh")
            1112,  // seribu seratus dua belas
            1115,  // seribu seratus lima belas
            1119,  // seribu seratus sembilan belas
            1211,  // seribu dua ratus sebelas
            1311,  // seribu tiga ratus sebelas
            2110,  // dua ribu seratus sepuluh
            
            // Kombinasi ribuan + ratusan + puluhan (full complexity)
            1120,  // seribu seratus dua puluh
            1125,  // seribu seratus dua puluh lima
            1234,  // seribu dua ratus tiga puluh empat
            2345,  // dua ribu tiga ratus empat puluh lima
            3456,  // tiga ribu empat ratus lima puluh enam
            4567,  // empat ribu lima ratus enam puluh tujuh
            5678,  // lima ribu enam ratus tujuh puluh delapan
            6789,  // enam ribu tujuh ratus delapan puluh sembilan
            
            // Pattern berulang (testing consistency)
            1212,  // seribu dua ratus dua belas
            2323,  // dua ribu tiga ratus dua puluh tiga
            3434,  // tiga ribu empat ratus tiga puluh empat
            
            // High numbers
            9999,  // sembilan ribu sembilan ratus sembilan puluh sembilan
        ];
        
        $antrianLoket = [];
        foreach ($loketBugNumbers as $index => $nomor) {
            $antrianLoket[] = [
                'type' => 'Loket',
                'noantrian' => $nomor,
                'no_rkm_medis' => null,
                'postdate' => $today,
                'start_time' => $baseTime->copy()->addMinutes($index * 2)->format('H:i:s'),
                'end_time' => '00:00:00',
                'status' => '0',
                'loket' => '0',
                'category' => 'reguler',
            ];
        }
        
        // =============================================
        // LOKET VIP - Critical Bug Numbers
        // =============================================
        $vipBugNumbers = [
            // Most problematic numbers
            1000,  // seribu
            1010,  // seribu sepuluh
            1011,  // seribu sebelas
            1100,  // seribu seratus (CRITICAL!)
            1110,  // seribu seratus sepuluh (CRITICAL!)
            1111,  // seribu seratus sebelas (CRITICAL!)
            1234,  // seribu dua ratus tiga puluh empat
            2000,  // dua ribu
            2111,  // dua ribu seratus sebelas
            9999,  // max number
        ];
        
        $antrianVIP = [];
        foreach ($vipBugNumbers as $index => $nomor) {
            $antrianVIP[] = [
                'type' => 'LoketVIP',
                'noantrian' => $nomor,
                'no_rkm_medis' => null,
                'postdate' => $today,
                'start_time' => $baseTime->copy()->addMinutes($index * 3)->format('H:i:s'),
                'end_time' => '00:00:00',
                'status' => '0',
                'loket' => '0',
                'category' => 'vip',
            ];
        }
        
        // =============================================
        // CS - Selected Bug Numbers
        // =============================================
        $csBugNumbers = [
            111,   // seratus sebelas
            1111,  // seribu seratus sebelas
            1234,  // kompleks
            2020,  // dua ribu dua puluh
        ];
        
        $antrianCS = [];
        foreach ($csBugNumbers as $index => $nomor) {
            $antrianCS[] = [
                'type' => 'CS',
                'noantrian' => $nomor,
                'no_rkm_medis' => null,
                'postdate' => $today,
                'start_time' => $baseTime->copy()->addMinutes($index * 5)->format('H:i:s'),
                'end_time' => '00:00:00',
                'status' => '0',
                'loket' => '0',
                'category' => 'reguler',
            ];
        }
        
        // =============================================
        // APOTEK - High Volume Test
        // =============================================
        $apotekBugNumbers = [
            1000,  // boundary
            1111,  // critical
            5678,  // mid-range complex
            9999,  // max
        ];
        
        $antrianApotek = [];
        foreach ($apotekBugNumbers as $index => $nomor) {
            $antrianApotek[] = [
                'type' => 'Apotek',
                'noantrian' => $nomor,
                'no_rkm_medis' => null,
                'postdate' => $today,
                'start_time' => $baseTime->copy()->addMinutes($index * 4)->format('H:i:s'),
                'end_time' => '00:00:00',
                'status' => '0',
                'loket' => '0',
                'category' => 'reguler',
            ];
        }
        
        // =============================================
        // INSERT ALL DATA
        // =============================================
        DB::table('ml_antrian_loket')->insert(array_merge(
            $antrianLoket,
            $antrianVIP,
            $antrianCS,
            $antrianApotek
        ));
        
        $this->command->info('✅ Antrian test seeder completed!');
        $this->command->info('📊 Total records:');
        $this->command->info('   - Loket: ' . count($antrianLoket));
        $this->command->info('   - LoketVIP: ' . count($antrianVIP));
        $this->command->info('   - CS: ' . count($antrianCS));
        $this->command->info('   - Apotek: ' . count($antrianApotek));
        $this->command->info('   - TOTAL: ' . (count($antrianLoket) + count($antrianVIP) + count($antrianCS) + count($antrianApotek)));
    }
}