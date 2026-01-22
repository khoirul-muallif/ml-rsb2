<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AntrianVIPSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Loket VIP
            [
                'module' => 'anjungan',
                'field' => 'antrian_loket_vip',
                'value' => '1,2'
            ],
            [
                'module' => 'anjungan',
                'field' => 'panggil_loket_vip',
                'value' => '1'
            ],
            [
                'module' => 'anjungan',
                'field' => 'panggil_loket_vip_nomor',
                'value' => '0'
            ],
            
            // CS VIP
            [
                'module' => 'anjungan',
                'field' => 'antrian_cs_vip',
                'value' => '1,2'
            ],
            [
                'module' => 'anjungan',
                'field' => 'panggil_cs_vip',
                'value' => '1'
            ],
            [
                'module' => 'anjungan',
                'field' => 'panggil_cs_vip_nomor',
                'value' => '0'
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('mlite_settings')->updateOrInsert(
                [
                    'module' => $setting['module'],
                    'field' => $setting['field']
                ],
                [
                    'value' => $setting['value']
                ]
            );
        }

        $this->command->info('✅ VIP settings seeded successfully!');
    }
}