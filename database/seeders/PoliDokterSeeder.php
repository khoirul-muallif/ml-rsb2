<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliDokterSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('dokter')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        $dokterList = [
            ['kd_dokter' => 'DR001', 'nm_dokter' => 'dr. Budi Santoso, Sp.A',      'jk' => 'L'],
            ['kd_dokter' => 'DR002', 'nm_dokter' => 'dr. Siti Rahayu, Sp.PD',      'jk' => 'P'],
            ['kd_dokter' => 'DR003', 'nm_dokter' => 'dr. Hendra Kusuma, Sp.OG',    'jk' => 'L'],
            ['kd_dokter' => 'DR004', 'nm_dokter' => 'dr. Anita Dewi, Sp.B',        'jk' => 'P'],
            ['kd_dokter' => 'DR005', 'nm_dokter' => 'dr. Rizky Pratama, Sp.S',     'jk' => 'L'],
            ['kd_dokter' => 'DR006', 'nm_dokter' => 'dr. Wulandari, Sp.JP',        'jk' => 'P'],
            ['kd_dokter' => 'DR007', 'nm_dokter' => 'dr. Fajar Nugroho, Sp.M',     'jk' => 'L'],
            ['kd_dokter' => 'DR008', 'nm_dokter' => 'dr. Lestari Ningrum, Sp.THT', 'jk' => 'P'],
            ['kd_dokter' => 'DR009', 'nm_dokter' => 'dr. Agus Hermanto',            'jk' => 'L'],
            ['kd_dokter' => 'DR010', 'nm_dokter' => 'drg. Maya Putri',             'jk' => 'P'],
        ];

        foreach ($dokterList as $d) {
            DB::connection('poli')->table('dokter')->insert([
                'kd_dokter'      => $d['kd_dokter'],
                'nm_dokter'      => $d['nm_dokter'],
                'jk'             => $d['jk'],
                'tmp_lahir'      => 'Semarang',
                'tgl_lahir'      => '1980-01-01',
                'gol_drh'        => 'O',
                'agama'          => 'Islam',
                'almt_tgl'       => 'Jl. Contoh No. 1, Semarang',
                'no_telp'        => '08' . rand(100000000, 999999999),
                'stts_nikah'     => 'MENIKAH',
                'kd_sps'         => null,
                'alumni'         => 'UNDIP',
                'no_ijn_praktek' => 'SIP/' . $d['kd_dokter'] . '/2024',
                'status'         => '1',
            ]);
        }
    }
}