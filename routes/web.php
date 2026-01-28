<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anjungan\PemanggilAntrianController;
use App\Http\Controllers\Anjungan\DisplayAntrianController;
use App\Http\Controllers\Anjungan\AntrianLoketController;

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
     * ✅ Halaman dashboard utama
     * GET /anjungan
     */
    Route::get('/', function () {
        return view('anjungan.index');
    })->name('index');
    
    // ================================================
    // MENU & LAYANAN ANTRIAN (ANJUNGAN PASIEN)
    // ================================================
    
    /**
     * ✅ Menu utama - Pilih grup layanan
     * GET /anjungan/layanan/menu
     */
    Route::get('/layanan/menu', [AntrianLoketController::class, 'menu'])
        ->name('layanan.menu');
    
    /**
     * ✅ Halaman per grup layanan
     * GET /anjungan/layanan/loket
     * GET /anjungan/layanan/cs
     * GET /anjungan/layanan/apotek
     */
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/loket', [AntrianLoketController::class, 'loket'])->name('loket');
        Route::get('/cs', [AntrianLoketController::class, 'cs'])->name('cs');
        Route::get('/apotek', [AntrianLoketController::class, 'apotek'])->name('apotek');
    });

    // ================================================
    // PEMANGGIL ANTRIAN (UNTUK PETUGAS)
    // ================================================
    
    /**
     * ✅ Menu pemanggil - Pilih loket yang akan dioperasikan
     * GET /anjungan/pemanggil/menu
     */
    Route::get('/pemanggil/menu', function () {
        return view('anjungan.pemanggil.menu');
    })->name('pemanggil.menu');
    
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
    
    // ================================================
    // DISPLAY MONITOR (UNTUK TV/LAYAR TUNGGU)
    // ================================================
    
    /**
     * ✅ Menu display - Pilih display yang akan ditampilkan
     * GET /anjungan/display/menu
     */
    Route::get('/display/menu', function () {
        return view('anjungan.display.menu');
    })->name('display.menu');
    
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
        // ANTRIAN API (untuk anjungan pasien)
        // -----------------------------------------------
        
        /**
         * API: Ambil nomor antrian baru
         * POST /anjungan/api/ambil
         */
        Route::post('/ambil', [AntrianLoketController::class, 'ambil'])
            ->name('ambil');
        
        /**
         * API: Get summary antrian hari ini
         * GET /anjungan/api/summary
         */
        Route::get('/summary', [AntrianLoketController::class, 'summary'])
            ->name('summary');
        
        /**
         * API: Get info antrian by ID
         * GET /anjungan/api/info/{id}
         */
        Route::get('/info/{id}', [AntrianLoketController::class, 'info'])
            ->name('info');
        
        /**
         * API: Cek nomor antrian
         * POST /anjungan/api/cek
         */
        Route::post('/cek', [AntrianLoketController::class, 'cek'])
            ->name('cek');
        
        // -----------------------------------------------
        // PEMANGGIL API (untuk halaman pemanggil)
        // -----------------------------------------------
        
        /**
         * Set panggil antrian (dari tombol next/lompat)
         * POST /anjungan/api/setpanggil
         */
        Route::post('/setpanggil', [PemanggilAntrianController::class, 'setPanggil'])
            ->name('setpanggil');
        
        /**
         * Simpan nomor rekam medis
         * POST /anjungan/api/simpannorm
         */
        Route::post('/simpannorm', [PemanggilAntrianController::class, 'simpanNoRM'])
            ->name('simpannorm');
        
        /**
         * Reset antrian untuk type tertentu
         * POST /anjungan/api/reset/{type}
         */
        Route::post('/reset/{type}', [PemanggilAntrianController::class, 'resetAntrian'])
            ->name('reset');
        
        // -----------------------------------------------
        // DISPLAY API (untuk halaman display monitor)
        // -----------------------------------------------
        
        /**
         * Get antrian yang sedang dipanggil (untuk display)
         * GET /anjungan/api/getdisplay?type=Loket
         */
        Route::get('/getdisplay', [DisplayAntrianController::class, 'getDisplay'])
            ->name('getdisplay');
        
        /**
         * Set display selesai (acknowledge dari display)
         * POST /anjungan/api/setdisplayselesai
         */
        Route::post('/setdisplayselesai', [DisplayAntrianController::class, 'setDisplaySelesai'])
            ->name('setdisplayselesai');
        
        /**
         * Get statistik antrian (untuk display stats)
         * GET /anjungan/api/getstats?type=Loket
         */
        Route::get('/getstats', [DisplayAntrianController::class, 'getStats'])
            ->name('getstats');
    });
});