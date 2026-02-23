<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel: antripoli
 * Tidak ada PK tunggal di DB (Engine: MyISAM), kolom no_rawat dijadikan acuan.
 *
 * Kolom status:
 *   '1' → baru dipanggil (trigger suara di display)
 *   '0' → sudah di-acknowledge / sudah dibunyikan
 */
class AntriPoli extends Model
{
    protected $connection   = 'poli';
    protected $table        = 'antripoli';
    protected $primaryKey   = 'no_rawat';   // pakai ini sebagai acuan update/delete
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    protected $fillable = [
        'kd_dokter',
        'kd_poli',
        'status',
        'no_rawat',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function poliklinik(): BelongsTo
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    // ----------------------------------------------------------------
    // Scope
    // ----------------------------------------------------------------

    /** Pasien yang baru dipanggil (status = '1') — trigger bunyi */
    public function scopeBaru($query)
    {
        return $query->where('status', '1');
    }

    // ----------------------------------------------------------------
    // Helper: reset status semua antri poli menjadi '0'
    // Dipanggil setelah display membunyikan nomor antrian.
    // ----------------------------------------------------------------

    public static function resetStatus(string $kdPoli, string $kdDokter): int
    {
        return static::on('poli')
            ->where('kd_poli', $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->update(['status' => '0']);
    }
}