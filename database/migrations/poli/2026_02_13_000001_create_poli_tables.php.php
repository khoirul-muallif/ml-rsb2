<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel-tabel poliklinik (DB tiruan untuk development)
 *
 * Koneksi : poli  (didefinisikan di config/database.php)
 * Charset : latin1 / latin1_swedish_ci  (sesuai DB asli)
 *
 * Cara jalankan KHUSUS koneksi poli:
 *   php artisan migrate --database=poli --path=database/migrations/poli
 *
 * CATATAN:
 *  - Simpan file ini di: database/migrations/poli/
 *  - Di production, tabel sudah ada di DB SIK — jangan dijalankan ulang.
 */
return new class extends Migration
{
    protected $connection = 'poli';

    public function up(): void
    {
        // ============================================================
        // 1. poliklinik
        // ============================================================
        Schema::connection('poli')->create('poliklinik', function (Blueprint $table) {
            $table->string('kd_poli', 5)->primary();
            $table->string('nm_poli', 50)->nullable();
            $table->double('registrasi')->default(0);
            $table->double('registrasilama')->default(0);
            $table->enum('status', ['0', '1'])->default('1');
            $table->index('nm_poli');
            $table->index('kd_poli');
        });

        // ============================================================
        // 2. dokter
        // ============================================================
        Schema::connection('poli')->create('dokter', function (Blueprint $table) {
            $table->string('kd_dokter', 20)->primary();
            $table->string('nm_dokter', 50)->nullable();
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('tmp_lahir', 20)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('gol_drh', ['A', 'B', 'O', 'AB', '-'])->nullable();
            $table->string('agama', 12)->nullable();
            $table->string('almt_tgl', 100)->nullable();
            $table->string('no_telp', 13)->nullable();
            $table->enum('stts_nikah', [
                'BELUM MENIKAH', 'MENIKAH', 'JANDA', 'DUDHA', 'JOMBLO'
            ])->nullable();
            $table->char('kd_sps', 5)->nullable();
            $table->string('alumni', 60)->nullable();
            $table->string('no_ijn_praktek', 120)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->index('nm_dokter');
            $table->index('status');
        });

        // ============================================================
        // 3. jadwal  (PK compound)
        // ============================================================
        Schema::connection('poli')->create('jadwal', function (Blueprint $table) {
            $table->string('kd_dokter', 20);
            $table->enum('hari_kerja', [
                'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'AKHAD'
            ])->default('SENIN');
            $table->time('jam_mulai')->default('00:00:00');
            $table->time('jam_selesai')->nullable();
            $table->char('kd_poli', 5)->nullable();
            $table->integer('kuota')->nullable();
            $table->string('keterangan', 200)->nullable();

            $table->primary(['kd_dokter', 'hari_kerja', 'jam_mulai']);
            $table->index('kd_dokter');
            $table->index('kd_poli');

            $table->foreign('kd_dokter')
                  ->references('kd_dokter')->on('dokter')
                  ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('kd_poli')
                  ->references('kd_poli')->on('poliklinik')
                  ->restrictOnDelete()->cascadeOnUpdate();
        });

        // ============================================================
        // 4. pasien
        // ============================================================
        Schema::connection('poli')->create('pasien', function (Blueprint $table) {
            $table->string('no_rkm_medis', 15)->primary();
            $table->string('nm_pasien', 100)->nullable();
            $table->string('no_ktp', 20)->nullable();
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('tmp_lahir', 15)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nm_ibu', 40)->default('');
            $table->string('alamat', 200)->nullable();
            $table->enum('gol_darah', ['A', 'B', 'O', 'AB', '-'])->nullable();
            $table->string('pekerjaan', 60)->nullable();
            $table->enum('stts_nikah', [
                'BELUM MENIKAH', 'MENIKAH', 'JANDA', 'DUDHA', 'JOMBLO'
            ])->nullable();
            $table->string('agama', 12)->nullable();
            $table->date('tgl_daftar')->nullable();
            $table->string('no_tlp', 40)->nullable();
            $table->string('umur', 30)->default('');
            $table->enum('pnd', [
                'TS','TK','SD','SMP','SMA','SLTA/SEDERAJAT',
                'D1','D2','D3','D4','S1','S2','S3','-'
            ])->default('-');
            $table->string('kd_pj', 3)->default('');
            $table->string('no_peserta', 25)->nullable();
            $table->integer('kd_kel')->default(0);
            $table->integer('kd_kec')->default(0);
            $table->integer('kd_kab')->default(0);
            $table->string('pekerjaanpj', 35)->default('');
            $table->string('alamatpj', 100)->default('');
            $table->string('kelurahanpj', 60)->default('');
            $table->string('kecamatanpj', 60)->default('');
            $table->string('kabupatenpj', 60)->default('');
            $table->string('perusahaan_pasien', 8)->default('');
            $table->integer('suku_bangsa')->default(0);
            $table->integer('bahasa_pasien')->default(0);
            $table->integer('cacat_fisik')->default(0);
            $table->string('email', 50)->default('');
            $table->string('nip', 30)->default('');
            $table->integer('kd_prop')->default(0);
            $table->string('propinsipj', 30)->default('');
            $table->enum('keluarga', ['AYAH','IBU','ISTRI','SUAMI','SAUDARA','ANAK'])->nullable();
            $table->string('namakeluarga', 50)->default('');
            $table->index('nm_pasien');
            $table->index('no_ktp');
        });

        // ============================================================
        // 5. reg_periksa
        // ============================================================
        Schema::connection('poli')->create('reg_periksa', function (Blueprint $table) {
            $table->string('no_rawat', 17)->primary();
            $table->string('no_reg', 8)->nullable();
            $table->date('tgl_registrasi')->nullable();
            $table->time('jam_reg')->nullable();
            $table->string('kd_dokter', 20)->nullable();
            $table->string('no_rkm_medis', 15)->nullable();
            $table->char('kd_poli', 5)->nullable();
            $table->string('p_jawab', 100)->nullable();
            $table->string('almt_pj', 200)->nullable();
            $table->string('hubunganpj', 20)->nullable();
            $table->double('biaya_reg')->nullable();
            $table->enum('stts', [
                'Belum', 'Sudah', 'Batal', 'Berkas Diterima',
                'Dirujuk', 'Meninggal', 'Dirawat', 'Pulang Paksa', 'Berkas Terkirim'
            ])->nullable();
            $table->enum('stts_daftar', ['-', 'Lama', 'Baru'])->default('-');
            $table->enum('status_lanjut', ['Ralan', 'Ranap'])->default('Ralan');
            $table->char('kd_pj', 3)->default('');
            $table->integer('umurdaftar')->nullable();
            $table->enum('sttsumur', ['Th', 'Bl', 'Hr'])->nullable();
            $table->enum('status_bayar', ['Sudah Bayar', 'Belum Bayar'])->default('Belum Bayar');
            $table->enum('status_poli', ['Lama', 'Baru'])->default('Baru');

            $table->index('no_rkm_medis');
            $table->index('kd_poli');
            $table->index('kd_dokter');
            $table->index('tgl_registrasi');
            $table->index('no_reg');

            $table->foreign('kd_poli')
                  ->references('kd_poli')->on('poliklinik')
                  ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('kd_dokter')
                  ->references('kd_dokter')->on('dokter')
                  ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('no_rkm_medis')
                  ->references('no_rkm_medis')->on('pasien')
                  ->restrictOnDelete()->cascadeOnUpdate();
        });

        // ============================================================
        // 6. antripoli
        // ============================================================
        Schema::connection('poli')->create('antripoli', function (Blueprint $table) {
            $table->string('kd_dokter', 20)->nullable();
            $table->char('kd_poli', 5)->nullable();
            $table->enum('status', ['0', '1'])->nullable();
            $table->string('no_rawat', 17);
            $table->index('kd_dokter');
            $table->index('kd_poli');
            $table->index('no_rawat');
        });

        // ============================================================
        // 7. setting
        // ============================================================
        Schema::connection('poli')->create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi', 100)->nullable();
            $table->string('alamat_instansi', 200)->nullable();
            $table->string('kabupaten', 60)->nullable();
            $table->string('propinsi', 60)->nullable();
            $table->string('kontak', 60)->nullable();
            $table->string('email', 60)->nullable();
            $table->binary('logo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('poli')->dropIfExists('antripoli');
        Schema::connection('poli')->dropIfExists('reg_periksa');
        Schema::connection('poli')->dropIfExists('pasien');
        Schema::connection('poli')->dropIfExists('jadwal');
        Schema::connection('poli')->dropIfExists('dokter');
        Schema::connection('poli')->dropIfExists('poliklinik');
        Schema::connection('poli')->dropIfExists('setting');
    }
};