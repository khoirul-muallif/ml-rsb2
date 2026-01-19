<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemanggilAntrianController;


Route::get('/', function () {
    return view('welcome');
});


/**
 * Routes untuk Anjungan Pemanggil Antrian
 * Prefix: /anjungan
 */

Route::prefix('anjungan')->name('anjungan.')->group(function () {
    
    // Halaman pemanggil antrian
    Route::get('/pemanggil', [PemanggilAntrianController::class, 'index'])
        ->name('pemanggil');
    
    // API Routes (untuk AJAX calls)
    Route::prefix('api')->name('api.')->group(function () {
        
        // Set panggil (dipanggil saat klik tombol panggil)
        Route::post('/setpanggil', [PemanggilAntrianController::class, 'setPanggil'])
            ->name('setpanggil');
        
        // Simpan nomor rekam medis
        Route::post('/simpannorm', [PemanggilAntrianController::class, 'simpanNoRM'])
            ->name('simpannorm');
        
        // Reset antrian (loket, cs, apotek)
        Route::post('/reset/{type}', [PemanggilAntrianController::class, 'resetAntrian'])
            ->name('reset')
            ->where('type', 'loket|cs|apotek|igd');
    });
});

/**
 * Contoh URL yang dihasilkan:
 * 
 * - GET  /anjungan/pemanggil
 * - GET  /anjungan/pemanggil?show=panggil_loket
 * - GET  /anjungan/pemanggil?show=panggil_loket&loket=1
 * - GET  /anjungan/pemanggil?show=panggil_loket&antrian=5&reset=1
 * 
 * - POST /anjungan/api/setpanggil
 * - POST /anjungan/api/simpannorm
 * - POST /anjungan/api/reset/loket
 */