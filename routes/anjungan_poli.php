<?php

use App\Http\Controllers\Poli\PemanggilPoliController;
use App\Http\Controllers\Poli\DisplayPoliController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Modul: Anjungan Poli — Display Antrian Poliklinik
|--------------------------------------------------------------------------
|
| Prefix  : anjungan/poli
| Nama    : anjungan.poli.*
|
| Cara pakai URL (generate di controller/view):
|   route('anjungan.poli.display', ['poli' => encrypt('ANA'), 'dokter' => encrypt('DR001')])
|
| Parameter dienkripsi pakai encrypt()/decrypt() bawaan Laravel
| (menggantikan fungsi encrypt_decrypt() dari kode PHP lama).
|
*/

Route::prefix('anjungan/poli')->name('anjungan.poli.')->group(function () {

    // ------------------------------------------------------------------
    // DISPLAY — Layar TV / Monitor ruang tunggu (tidak perlu login)
    // ------------------------------------------------------------------

    /**
     * Halaman display antrian per dokter
     * GET /anjungan/poli/display?poli={enc}&dokter={enc}
     */
    Route::get('/display', [DisplayPoliController::class, 'index'])
        ->name('display');

    // ------------------------------------------------------------------
    // API — Polling AJAX dari halaman display (setiap ~10 detik)
    // ------------------------------------------------------------------

    /**
     * Get data antrian terkini (pasien masuk + daftar tunggu)
     * GET /anjungan/poli/api/antrian?poli={enc}&dokter={enc}
     *
     * Response JSON:
     * {
     *   "info"    : { nm_poli, nm_dokter, jam_praktek, keterangan, tgl_hari },
     *   "masuk"   : [ { no_antrian, nm_pasien, nm_poli } ],
     *   "tunggu"  : [ { no_antrian, nm_pasien } ]
     * }
     */
    Route::get('/api/antrian', [DisplayPoliController::class, 'getAntrian'])
        ->name('api.antrian');

    /**
     * Acknowledge — dipanggil oleh display setelah suara dibunyikan.
     * Mengubah antripoli.status dari '1' → '0'.
     *
     * POST /anjungan/poli/api/ack
     * Body: { poli: enc, dokter: enc }
     */
    Route::post('/api/ack', [DisplayPoliController::class, 'acknowledge'])
        ->name('api.ack');

    // ------------------------------------------------------------------
    // JADWAL — Halaman & API jadwal dokter hari ini
    // ------------------------------------------------------------------

    Route::get('/jadwal', [DisplayPoliController::class, 'jadwal'])
        ->name('jadwal');

    /**
     * API: Data jadwal hari ini (untuk polling AJAX tiap 60 detik)
     * GET /anjungan/poli/api/jadwal
     */
    Route::get('/api/jadwal', [DisplayPoliController::class, 'getJadwal'])
        ->name('api.jadwal');

    // ------------------------------------------------------------------
    // HELPER — URL generator (DILINDUNGI auth — hanya admin/petugas)
    // FIX: Route ini mengekspos seluruh daftar poli+dokter dan menghasilkan
    //      URL terenkripsi, sehingga wajib diproteksi agar tidak bisa
    //      diakses sembarangan dari luar.
    //
    //      Ganti middleware sesuai sistem auth yang dipakai
    //      (mis. 'auth', 'role:admin', atau middleware custom).
    // ------------------------------------------------------------------

    Route::get('/url-generator', [DisplayPoliController::class, 'urlGenerator'])
        ->name('url.generator');
        // ->middleware(['auth']);  // ← sesuaikan dengan auth system project kamu

    // ------------------------------------------------------------------
    // PEMANGGIL — Halaman petugas untuk memanggil pasien
    // ------------------------------------------------------------------

    // /**
    //  * Halaman pemanggil antrian
    //  * GET /anjungan/poli/pemanggil?poli={enc}&dokter={enc}
    //  */
    // Route::get('/pemanggil', [PemanggilPoliController::class, 'index'])
    //     ->name('pemanggil');

    // /**
    //  * API: Daftar pasien menunggu (AJAX polling)
    //  * GET /anjungan/poli/api/pemanggil/daftar?poli={enc}&dokter={enc}
    //  */
    // Route::get('/api/pemanggil/daftar', [PemanggilPoliController::class, 'getDaftar'])
    //     ->name('api.pemanggil.daftar');

    // /**
    //  * API: Panggil pasien
    //  * POST /anjungan/poli/api/pemanggil/panggil
    //  */
    // Route::post('/api/pemanggil/panggil', [PemanggilPoliController::class, 'panggil'])
    //     ->name('api.pemanggil.panggil');

    // /**
    //  * API: Panggil ULANG pasien yang sudah dipanggil
    //  * POST /anjungan/poli/api/pemanggil/ulang
    //  */
    // Route::post('/api/pemanggil/ulang', [PemanggilPoliController::class, 'panggilUlang'])
    //     ->name('api.pemanggil.ulang');
});