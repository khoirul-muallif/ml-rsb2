<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliPasienSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('poli')->table('pasien')->truncate();
        DB::connection('poli')->statement('SET FOREIGN_KEY_CHECKS=1');

        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $noRkm = str_pad($i, 6, '0', STR_PAD_LEFT);
            DB::connection('poli')->table('pasien')->insert([
                'no_rkm_medis'      => $noRkm,
                'nm_pasien'         => strtoupper($faker->name()),
                'no_ktp'            => $faker->nik(),
                'jk'                => $faker->randomElement(['L', 'P']),
                'tmp_lahir'         => $faker->city(),
                'tgl_lahir'         => $faker->dateTimeBetween('-70 years', '-17 years')->format('Y-m-d'),
                'nm_ibu'            => strtoupper($faker->firstNameFemale()),
                'alamat'            => $faker->address(),
                'gol_darah'         => $faker->randomElement(['A', 'B', 'O', 'AB']),
                'pekerjaan'         => $faker->jobTitle(),
                'stts_nikah'        => $faker->randomElement(['BELUM MENIKAH', 'MENIKAH']),
                'agama'             => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                'tgl_daftar'        => now()->subDays(rand(1, 365))->format('Y-m-d'),
                'no_tlp'            => '08' . rand(100000000, 999999999),
                'umur'              => rand(17, 70) . ' Th',
                'pnd'               => $faker->randomElement(['SD', 'SMP', 'SMA', 'S1', 'D3']),
                'kd_pj'             => 'UMM',
                'no_peserta'        => null,
                'kd_kel'            => 0,
                'kd_kec'            => 0,
                'kd_kab'            => 0,
                'pekerjaanpj'       => '',
                'alamatpj'          => '',
                'kelurahanpj'       => '',
                'kecamatanpj'       => '',
                'kabupatenpj'       => '',
                'perusahaan_pasien' => '',
                'suku_bangsa'       => 0,
                'bahasa_pasien'     => 0,
                'cacat_fisik'       => 0,
                'email'             => $faker->safeEmail(),
                'nip'               => '',
                'kd_prop'           => 0,
                'propinsipj'        => '',
                'keluarga'          => null,
                'namakeluarga'      => '',
            ]);
        }
    }
}