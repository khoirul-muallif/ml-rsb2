<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemanggilAntrianController;
use App\Http\Controllers\DisplayAntrianController;

Route::get('/', function () {
    return view('welcome');
});


/**
 * Routes untuk Anjungan Pemanggil Antrian
 * Prefix: /anjungan
 */

Route::prefix('anjungan')->name('anjungan.')->group(function () {
    Route::get('/', function () {
    return view('anjungan.index');
})->name('anjungan.index');
    // Pemanggil normal
    Route::get('/pemanggil', [PemanggilAntrianController::class, 'index'])->name('pemanggil');
       // Display normal
    Route::get('/display', [DisplayAntrianController::class, 'index'])->name('display');
    
    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('/setpanggil', [PemanggilAntrianController::class, 'setPanggil'])->name('setpanggil');
        Route::post('/simpannorm', [PemanggilAntrianController::class, 'simpanNoRM'])->name('simpannorm');
        Route::post('/reset/{type}', [PemanggilAntrianController::class, 'resetAntrian'])->name('reset');
        Route::get('/getdisplay', [DisplayAntrianController::class, 'getDisplay'])->name('getdisplay');
        Route::post('/setdisplayselesai', [DisplayAntrianController::class, 'setDisplaySelesai'])->name('setdisplayselesai');
        
    });
});
