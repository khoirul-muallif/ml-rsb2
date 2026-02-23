<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliAntriPoliSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('antripoli')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        $regList = DB::connection('poli')->table('reg_periksa')
            ->whereDate('tgl_registrasi', now()->format('Y-m-d'))
            ->whereNotIn('stts', ['Sudah', 'Belum', 'Berkas Terkirim'])
            ->get();

        if ($regList->isEmpty()) {
            $this->command->warn('Tidak ada reg_periksa aktif. Pastikan PoliRegPeriksaSeeder sudah dijalankan.');
            return;
        }

        foreach ($regList as $reg) {
            DB::connection('poli')->table('antripoli')->insert([
                'kd_dokter' => $reg->kd_dokter,
                'kd_poli'   => $reg->kd_poli,
                'status'    => '0',
                'no_rawat'  => $reg->no_rawat,
            ]);
        }

        // Set 1 pasien pertama status='1' untuk simulasi panggilan
        $first = DB::connection('poli')->table('antripoli')->first();
        if ($first) {
            DB::connection('poli')->table('antripoli')
                ->where('no_rawat', $first->no_rawat)
                ->update(['status' => '1']);
            $this->command->info("Simulasi: no_rawat [{$first->no_rawat}] di-set status=1 (baru dipanggil).");
        }

        $this->command->info("Total {$regList->count()} baris antripoli berhasil dibuat.");
    }
}