<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Jalankan dengan:
 *   php artisan db:seed --class=PoliDatabaseSeeder
 */
class PoliDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PoliSettingSeeder::class,
            PoliPoliklinikSeeder::class,
            PoliDokterSeeder::class,
            PoliJadwalSeeder::class,
            PoliPasienSeeder::class,
            PoliRegPeriksaSeeder::class,
            PoliAntriPoliSeeder::class,
        ]);
    }
}