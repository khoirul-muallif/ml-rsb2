<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemanggilAntrianController;
use App\Http\Controllers\DisplayAntrianController;
use App\Http\Controllers\AntrianLoketController;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Routes untuk Anjungan Antrian Loket
 * Prefix: /anjungan
 */
Route::prefix('anjungan')->name('anjungan.')->group(function () {
    
    // ================================================
    // MAIN PAGES
    // ================================================
    
    /**
     * Halaman utama anjungan
     * GET /anjungan
     */
    Route::get('/', function () {
        return view('anjungan.index');
    })->name('index');
    
    /**
     * Halaman anjungan pasien - Ambil nomor antrian
     * GET /anjungan/loket
     */
    Route::get('/loket', [AntrianLoketController::class, 'index'])
        ->name('loket.index');

    /**
    * API: Ambil nomor antrian baru
    * POST /anjungan/loket/ambil
    */
    Route::post('/loket/ambil', [AntrianLoketController::class, 'ambil']);
    
    /**
     * Halaman pemanggil antrian (untuk petugas loket)
     * GET /anjungan/pemanggil?show=panggil_loket&loket=1
     * GET /anjungan/pemanggil?show=panggil_loket_vip&loket=1
     * GET /anjungan/pemanggil?show=panggil_cs&loket=2
     * GET /anjungan/pemanggil?show=panggil_cs_vip&loket=2
     * GET /anjungan/pemanggil?show=panggil_apotek&loket=1
     */
    Route::get('/pemanggil', [PemanggilAntrianController::class, 'index'])
        ->name('pemanggil');
    
    /**
     * Halaman display antrian (untuk TV/Monitor)
     * GET /anjungan/display (display gabungan semua loket)
     * GET /anjungan/display?show=loket (display loket saja)
     * GET /anjungan/display?show=loket_vip (display loket VIP saja)
     * GET /anjungan/display?show=cs (display CS saja)
     * GET /anjungan/display?show=cs_vip (display CS VIP saja)
     * GET /anjungan/display?show=apotek (display apotek saja)
     */
    Route::get('/display', [DisplayAntrianController::class, 'index'])
        ->name('display');
    
    // ================================================
    // API ENDPOINTS
    // ================================================
    
    Route::prefix('api')->name('api.')->group(function () {
        
        // -----------------------------------------------
        // PEMANGGIL API (untuk halaman pemanggil)
        // -----------------------------------------------
        
        /**
         * Set panggil antrian (dari tombol next/lompat)
         * POST /anjungan/api/setpanggil
         * Body: { noantrian, type, loket }
         */
        Route::post('/setpanggil', [PemanggilAntrianController::class, 'setPanggil'])
            ->name('setpanggil');
        
        /**
         * Simpan nomor rekam medis
         * POST /anjungan/api/simpannorm
         * Body: { noantrian, type, no_rkm_medis }
         */
        Route::post('/simpannorm', [PemanggilAntrianController::class, 'simpanNoRM'])
            ->name('simpannorm');
        
        /**
         * Reset antrian untuk type tertentu
         * POST /anjungan/api/reset/{type}
         * Example: POST /anjungan/api/reset/loket
         */
        Route::post('/reset/{type}', [PemanggilAntrianController::class, 'resetAntrian'])
            ->name('reset');
        
        // -----------------------------------------------
        // DISPLAY API (untuk halaman display monitor)
        // -----------------------------------------------
        
        /**
         * Get antrian yang sedang dipanggil (untuk display)
         * GET /anjungan/api/getdisplay?type=Loket
         * Response: { status, type, prefix, noantrian, loket, panggil[], id }
         */
        Route::get('/getdisplay', [DisplayAntrianController::class, 'getDisplay'])
            ->name('getdisplay');
        
        /**
         * Set display selesai (acknowledge dari display)
         * POST /anjungan/api/setdisplayselesai
         * Body: { id }
         */
        Route::post('/setdisplayselesai', [DisplayAntrianController::class, 'setDisplaySelesai'])
            ->name('setdisplayselesai');
        
        /**
         * Get statistik antrian (untuk display stats)
         * GET /anjungan/api/getstats?type=Loket
         * Response: { status, stats: { total, menunggu, diproses, selesai } }
         */
        Route::get('/getstats', [DisplayAntrianController::class, 'getStats'])
            ->name('getstats');
    });
});