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
        Schema::create('mlite_settings', function (Blueprint $table) {
            $table->id();
            $table->text('module')->nullable()->comment('settings, anjungan, api, website, jkn_mobile, dll');
            $table->text('field')->nullable()->comment('key/field name');
            $table->longText('value')->nullable()->comment('value');
            $table->timestamps();
            
            // Index untuk query yang lebih cepat
            $table->index('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlite_settings');
    }
};