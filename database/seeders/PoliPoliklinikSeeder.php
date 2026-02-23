<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliPoliklinikSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('poliklinik')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        DB::connection('poli')->table('poliklinik')->insert([
            ['kd_poli' => 'ANA', 'nm_poli' => 'Klinik Anak',                       'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'INT', 'nm_poli' => 'Klinik Penyakit Dalam',             'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'OBG', 'nm_poli' => 'Klinik Kandungan',                  'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'BED', 'nm_poli' => 'Klinik Bedah',                      'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'SAR', 'nm_poli' => 'Klinik Saraf',                      'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'JAN', 'nm_poli' => 'Klinik Jantung dan Pembuluh Darah', 'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'MAT', 'nm_poli' => 'Klinik Mata',                       'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'THT', 'nm_poli' => 'Klinik THT KL',                     'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'UMU', 'nm_poli' => 'Klinik Umum',                       'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
            ['kd_poli' => 'GIG', 'nm_poli' => 'Klinik Gigi',                       'registrasi' => 45000, 'registrasilama' => 45000, 'status' => '1'],
        ]);
    }
}