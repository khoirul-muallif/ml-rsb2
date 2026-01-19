<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MliteSetting;
use App\Models\AntrianLoket;
use Carbon\Carbon;

class AntrianLoketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================================
        // 1. Seed Settings untuk Anjungan
        // ========================================
        
        $settings = [
            // Settings Loket
            ['module' => 'anjungan', 'field' => 'antrian_loket', 'value' => '1,2,3,4,5'],
            ['module' => 'anjungan', 'field' => 'panggil_loket', 'value' => '1'],
            ['module' => 'anjungan', 'field' => 'panggil_loket_nomor', 'value' => '0'],
            
            // Settings CS
            ['module' => 'anjungan', 'field' => 'antrian_cs', 'value' => '1,2,3'],
            ['module' => 'anjungan', 'field' => 'panggil_cs', 'value' => '1'],
            ['module' => 'anjungan', 'field' => 'panggil_cs_nomor', 'value' => '0'],
            
            // Settings Apotek
            ['module' => 'anjungan', 'field' => 'antrian_apotek', 'value' => '1,2'],
            ['module' => 'anjungan', 'field' => 'panggil_apotek', 'value' => '1'],
            ['module' => 'anjungan', 'field' => 'panggil_apotek_nomor', 'value' => '0'],
            
            // Settings IGD (optional)
            ['module' => 'anjungan', 'field' => 'antrian_igd', 'value' => '1'],
            ['module' => 'anjungan', 'field' => 'panggil_igd', 'value' => '1'],
            ['module' => 'anjungan', 'field' => 'panggil_igd_nomor', 'value' => '0'],
            
            // Text display settings
            ['module' => 'anjungan', 'field' => 'text_loket', 'value' => 'Selamat datang di Loket Pendaftaran'],
            ['module' => 'anjungan', 'field' => 'text_poli', 'value' => 'Silahkan menunggu panggilan nomor antrian Anda'],
            ['module' => 'anjungan', 'field' => 'text_apotek', 'value' => 'Ambil obat di loket farmasi sesuai nomor antrian'],
        ];
        
        foreach ($settings as $setting) {
            MliteSetting::updateOrCreate(
                ['module' => $setting['module'], 'field' => $setting['field']],
                ['value' => $setting['value']]
            );
        }
        
        $this->command->info('✅ Settings anjungan berhasil di-seed!');
        
        // ========================================
        // 2. Seed Sample Data Antrian (Optional)
        // ========================================
        
        $this->seedSampleAntrian();
    }
    
    /**
     * Seed sample antrian untuk testing
     */
    private function seedSampleAntrian(): void
    {
        $today = Carbon::today();
        
        // Sample antrian Loket (Type: Loket, Prefix: A)
        $antrianLoket = [
            ['type' => 'Loket', 'noantrian' => '1', 'status' => '2', 'loket' => '1', 'start_time' => '08:00:00', 'end_time' => '08:05:00'],
            ['type' => 'Loket', 'noantrian' => '2', 'status' => '2', 'loket' => '2', 'start_time' => '08:01:00', 'end_time' => '08:06:00'],
            ['type' => 'Loket', 'noantrian' => '3', 'status' => '2', 'loket' => '1', 'start_time' => '08:02:00', 'end_time' => '08:07:00'],
            ['type' => 'Loket', 'noantrian' => '4', 'status' => '1', 'loket' => '3', 'start_time' => '08:03:00', 'end_time' => '08:08:00'],
            ['type' => 'Loket', 'noantrian' => '5', 'status' => '0', 'loket' => '0', 'start_time' => '08:04:00', 'end_time' => '00:00:00'],
            ['type' => 'Loket', 'noantrian' => '6', 'status' => '0', 'loket' => '0', 'start_time' => '08:05:00', 'end_time' => '00:00:00'],
        ];
        
        // Sample antrian CS (Type: CS, Prefix: B)
        $antrianCS = [
            ['type' => 'CS', 'noantrian' => '1', 'status' => '2', 'loket' => '1', 'start_time' => '08:00:00', 'end_time' => '08:10:00'],
            ['type' => 'CS', 'noantrian' => '2', 'status' => '1', 'loket' => '2', 'start_time' => '08:05:00', 'end_time' => '08:15:00'],
            ['type' => 'CS', 'noantrian' => '3', 'status' => '0', 'loket' => '0', 'start_time' => '08:10:00', 'end_time' => '00:00:00'],
        ];
        
        // Sample antrian Apotek (Type: Apotek, Prefix: F)
        $antrianApotek = [
            ['type' => 'Apotek', 'noantrian' => '1', 'status' => '2', 'loket' => '1', 'start_time' => '09:00:00', 'end_time' => '09:05:00', 'no_rkm_medis' => '123456'],
            ['type' => 'Apotek', 'noantrian' => '2', 'status' => '1', 'loket' => '1', 'start_time' => '09:05:00', 'end_time' => '09:10:00', 'no_rkm_medis' => '234567'],
            ['type' => 'Apotek', 'noantrian' => '3', 'status' => '0', 'loket' => '0', 'start_time' => '09:10:00', 'end_time' => '00:00:00'],
        ];
        
        // Merge all
        $allAntrian = array_merge($antrianLoket, $antrianCS, $antrianApotek);
        
        // Insert ke database
        foreach ($allAntrian as $antrian) {
            AntrianLoket::create([
                'type' => $antrian['type'],
                'noantrian' => $antrian['noantrian'],
                'no_rkm_medis' => $antrian['no_rkm_medis'] ?? null,
                'postdate' => $today,
                'start_time' => $antrian['start_time'],
                'end_time' => $antrian['end_time'],
                'status' => $antrian['status'],
                'loket' => $antrian['loket'],
            ]);
        }
        
        $this->command->info('✅ Sample antrian berhasil di-seed!');
        $this->command->info('   - Loket: ' . count($antrianLoket) . ' antrian');
        $this->command->info('   - CS: ' . count($antrianCS) . ' antrian');
        $this->command->info('   - Apotek: ' . count($antrianApotek) . ' antrian');
    }
}