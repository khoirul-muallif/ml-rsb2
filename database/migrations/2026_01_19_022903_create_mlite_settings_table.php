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
        Schema::create('ml_settings', function (Blueprint $table) {
            $table->id();
            
            // ✅ GANTI dari text() ke string()
            $table->string('module', 100)->nullable()->comment('settings, anjungan, api, website, jkn_mobile, dll');
            $table->string('field', 100)->nullable()->comment('key/field name');
            
            // ✅ value tetap longText karena perlu panjang
            $table->longText('value')->nullable()->comment('value');
            
            $table->timestamps();
            
            // ✅ Sekarang index bisa jalan tanpa error
            $table->index('module');
            $table->index('field'); // Bonus: field juga bisa di-index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_settings');
    }
};