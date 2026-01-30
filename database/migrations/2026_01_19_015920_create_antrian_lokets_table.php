<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ml_antrian_loket', function (Blueprint $table) {
            $table->id('kd');
            $table->string('type', 50)->comment('Loket, CS, Apotek, IGD');
            $table->string('noantrian', 50)->comment('Nomor urut antrian');
            $table->string('no_rkm_medis', 50)->nullable()->comment('No Rekam Medis pasien');
            $table->date('postdate')->comment('Tanggal antrian');
            $table->time('start_time')->comment('Waktu ambil antrian');
            $table->time('end_time')->default('00:00:00')->comment('Waktu dipanggil');
            $table->string('status', 10)->default('0')->comment('0=Belum, 1=Dipanggil, 2=Selesai, 3=Batal');
            $table->string('loket', 10)->default('0')->comment('Nomor loket pemanggil');
            
            $table->index(['type', 'postdate', 'status']);
            $table->index('no_rkm_medis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_antrian_loket');
    }
};