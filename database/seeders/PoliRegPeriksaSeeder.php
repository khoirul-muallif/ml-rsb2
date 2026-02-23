<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliRegPeriksaSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('reg_periksa')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        $namaHariMap = [
            'Sunday'    => 'AKHAD', 'Monday'    => 'SENIN',  'Tuesday'  => 'SELASA',
            'Wednesday' => 'RABU',  'Thursday'  => 'KAMIS',  'Friday'   => 'JUMAT',
            'Saturday'  => 'SABTU',
        ];
        $namaHari = $namaHariMap[now()->format('l')];

        $jadwalHariIni = DB::connection('poli')->table('jadwal')
            ->where('hari_kerja', $namaHari)
            ->get();

        if ($jadwalHariIni->isEmpty()) {
            $this->command->warn("Tidak ada jadwal untuk hari ini ({$namaHari}).");
            return;
        }

        $pasienIds = DB::connection('poli')->table('pasien')
            ->pluck('no_rkm_medis')
            ->toArray();

        $counter = 0;
        foreach ($jadwalHariIni as $jadwal) {
            for ($no = 1; $no <= 5; $no++) {
                $counter++;
                $noRkm   = $pasienIds[($counter - 1) % count($pasienIds)];
                $noRawat = now()->format('Y/m/d') . '/' . str_pad($counter, 5, '0', STR_PAD_LEFT);
                $noReg   = str_pad($no, 4, '0', STR_PAD_LEFT);

                $sttsOptions = [
                    'Belum', 'Berkas Terkirim',
                    'Berkas Diterima', 'Berkas Diterima', 'Sudah',
                ];

                DB::connection('poli')->table('reg_periksa')->insert([
                    'no_rawat'       => $noRawat,
                    'no_reg'         => $noReg,
                    'tgl_registrasi' => now()->format('Y-m-d'),
                    'jam_reg'        => now()->subMinutes(rand(10, 120))->format('H:i:s'),
                    'kd_dokter'      => $jadwal->kd_dokter,
                    'no_rkm_medis'   => $noRkm,
                    'kd_poli'        => $jadwal->kd_poli,
                    'p_jawab'        => '',
                    'almt_pj'        => '',
                    'hubunganpj'     => '',
                    'biaya_reg'      => 45000,
                    'stts'           => $sttsOptions[array_rand($sttsOptions)],
                    'stts_daftar'    => 'Lama',
                    'status_lanjut'  => 'Ralan',
                    'kd_pj'          => 'UMM',
                    'umurdaftar'     => rand(17, 70),
                    'sttsumur'       => 'Th',
                    'status_bayar'   => 'Sudah Bayar',
                    'status_poli'    => 'Lama',
                ]);
            }
        }

        $this->command->info("Berhasil membuat {$counter} reg_periksa untuk hari ini ({$namaHari}).");
    }
}