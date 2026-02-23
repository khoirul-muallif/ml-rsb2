<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('setting')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        DB::connection('poli')->table('setting')->insert([
            'nama_instansi'   => 'RSUD Contoh Kota Semarang',
            'alamat_instansi' => 'Jl. Kesehatan No. 1',
            'kabupaten'       => 'Kota Semarang',
            'propinsi'        => 'Jawa Tengah',
            'kontak'          => '024-1234567',
            'email'           => 'info@rsud-contoh.id',
            'logo'            => null,
        ]);
    }
}