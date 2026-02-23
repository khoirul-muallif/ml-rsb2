<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel: reg_periksa
 * PK   : no_rawat (varchar 17)
 *
 * Status pasien (stts):
 *   Belum           → daftar, belum ada di poli
 *   Berkas Terkirim → berkas dikirim ke poli, belum diterima petugas
 *   Berkas Diterima → berkas sudah di poli, masuk daftar tunggu
 *   Sudah           → selesai diperiksa
 *
 * PENTING: "Antrian Masuk" (sedang dipanggil) ditentukan dari tabel
 * antripoli.status, BUKAN dari stts reg_periksa. Scope antriMasuk
 * di sini hanya dipakai untuk filter join dengan antripoli.
 */
class RegPeriksa extends Model
{
    protected $connection   = 'poli';
    protected $table        = 'reg_periksa';
    protected $primaryKey   = 'no_rawat';
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    protected $casts = [
        'tgl_registrasi' => 'date',
    ];

    protected $fillable = [
        'no_reg', 'no_rawat', 'tgl_registrasi', 'jam_reg',
        'kd_dokter', 'no_rkm_medis', 'kd_poli',
        'p_jawab', 'almt_pj', 'hubunganpj', 'biaya_reg',
        'stts', 'stts_daftar', 'status_lanjut', 'kd_pj',
        'umurdaftar', 'sttsumur', 'status_bayar', 'status_poli',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function poliklinik(): BelongsTo
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function antriPoli(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AntriPoli::class, 'no_rawat', 'no_rawat');
    }

    // ----------------------------------------------------------------
    // Accessor: nomor antrian tampil sebagai "KD_POLI-NO_REG"
    // ----------------------------------------------------------------

    public function getNomorAntrianAttribute(): string
    {
        return "{$this->kd_poli}-{$this->no_reg}";
    }

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    /**
     * Pasien yang sedang AKTIF dipanggil di display.
     * Sumber kebenaran: antripoli (JOIN), bukan stts reg_periksa.
     * Hanya no_rawat yang ada di tabel antripoli yang dihitung.
     */
    public function scopeAntriMasuk($query)
    {
        return $query->whereExists(function ($sub) {
            $sub->selectRaw(1)
                ->from('antripoli')
                ->whereColumn('antripoli.no_rawat', 'reg_periksa.no_rawat');
        });
    }

    /**
     * Pasien yang masih menunggu dipanggil (tampil di daftar tunggu).
     * Termasuk: Belum, Berkas Terkirim, Berkas Diterima
     * Dikecualikan: yang sudah ada di antripoli (sudah dipanggil masuk)
     */
    public function scopeMenunggu($query)
    {
        return $query
            ->whereIn('stts', ['Belum', 'Berkas Terkirim', 'Berkas Diterima'])
            ->whereNotExists(function ($sub) {
                $sub->selectRaw(1)
                    ->from('antripoli')
                    ->whereColumn('antripoli.no_rawat', 'reg_periksa.no_rawat');
            });
    }

    /** Filter ke hari ini */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tgl_registrasi', today());
    }
}