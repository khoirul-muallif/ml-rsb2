<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MlSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ml_settings')->insert([
            [
                'id' => 1,
                'module' => 'anjungan',
                'field' => 'text_laboratorium',
                'value' => 'KESEMBUHAN ANDA HARAPAN KAMI - SELALU PATUHI PROTOKOL KESEHATAN',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'module' => 'anjungan',
                'field' => 'vidio',
                'value' => 'QUG9Tl3z0QE',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'module' => 'anjungan',
                'field' => 'antrian_farmasi',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'module' => 'anjungan',
                'field' => 'panggil_farmasi',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'module' => 'anjungan',
                'field' => 'panggil_farmasi_nomor',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'module' => 'anjungan',
                'field' => 'antrian_loket_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'module' => 'anjungan',
                'field' => 'panggil_loket_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'module' => 'anjungan',
                'field' => 'panggil_loket_vip_nomor',
                'value' => '4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'module' => 'anjungan',
                'field' => 'antrian_cs_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'module' => 'anjungan',
                'field' => 'panggil_cs_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'module' => 'anjungan',
                'field' => 'panggil_cs_vip_nomor',
                'value' => '4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'module' => 'anjungan',
                'field' => 'play_audio_loket',
                'value' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'module' => 'anjungan',
                'field' => 'play_audio_cs',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 14,
                'module' => 'anjungan',
                'field' => 'play_audio_loket_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 15,
                'module' => 'anjungan',
                'field' => 'play_audio_cs_vip',
                'value' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
