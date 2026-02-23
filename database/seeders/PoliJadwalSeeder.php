<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliJadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('jadwal')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        $dokterPoli = [
            'DR001' => 'ANA', 'DR002' => 'INT', 'DR003' => 'OBG',
            'DR004' => 'BED', 'DR005' => 'SAR', 'DR006' => 'JAN',
            'DR007' => 'MAT', 'DR008' => 'THT', 'DR009' => 'UMU',
            'DR010' => 'GIG',
        ];

        $hariList = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];

        $namaHariMap = [
            'Sunday'    => 'AKHAD', 'Monday'    => 'SENIN',  'Tuesday'  => 'SELASA',
            'Wednesday' => 'RABU',  'Thursday'  => 'KAMIS',  'Friday'   => 'JUMAT',
            'Saturday'  => 'SABTU',
        ];
        $hariIni = $namaHariMap[now()->format('l')];

        foreach ($dokterPoli as $kdDokter => $kdPoli) {
            // Ambil 2 hari lain selain hari ini
            $hariLain = collect($hariList)
                ->reject(fn($h) => $h === $hariIni)
                ->shuffle()
                ->take(2)
                ->values();

            $hariPraktek = collect([$hariIni])->merge($hariLain);

            foreach ($hariPraktek as $i => $hari) {
                $jamMulai   = $i % 2 === 0 ? '08:00:00' : '13:00:00';
                $jamSelesai = $i % 2 === 0 ? '12:00:00' : '16:00:00';

                DB::connection('poli')->table('jadwal')->insert([
                    'kd_dokter'   => $kdDokter,
                    'hari_kerja'  => $hari,
                    'jam_mulai'   => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'kd_poli'     => $kdPoli,
                    'kuota'       => 20,
                    'keterangan'  => 'Praktek Normal',
                ]);
            }
        }
    }
}