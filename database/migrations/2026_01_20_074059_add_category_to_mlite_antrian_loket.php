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
        Schema::table('ml_antrian_loket', function (Blueprint $table) {
            // Cek apakah kolom 'category' sudah ada
            if (!Schema::hasColumn('ml_antrian_loket', 'category')) {
                $table->string('category', 20)->default('reguler')->after('loket')->comment('reguler atau vip');
                $table->index('category', 'ml_antrian_loket_category_index');
                $table->index(['category', 'postdate'], 'idx_category_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ml_antrian_loket', function (Blueprint $table) {
            if (Schema::hasColumn('ml_antrian_loket', 'category')) {
                $table->dropIndex('ml_antrian_loket_category_index');
                $table->dropIndex('idx_category_date');
                $table->dropColumn('category');
            }
        });
    }
};