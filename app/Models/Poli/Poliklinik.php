<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tabel: poliklinik
 * PK   : kd_poli (char 5, bukan auto-increment)
 *
 * Contoh data:
 *   ANA → Klinik Anak
 *   INT → Klinik Penyakit Dalam
 *   dst.
 */
class Poliklinik extends Model
{
    protected $connection   = 'poli';
    protected $table        = 'poliklinik';
    protected $primaryKey   = 'kd_poli';
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    protected $fillable = [
        'kd_poli',
        'nm_poli',
        'registrasi',
        'registrasilama',
        'status',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kd_poli', 'kd_poli');
    }

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'kd_poli', 'kd_poli');
    }

    public function antriPoli(): HasMany
    {
        return $this->hasMany(AntriPoli::class, 'kd_poli', 'kd_poli');
    }

    // ----------------------------------------------------------------
    // Scope
    // ----------------------------------------------------------------

    /** Hanya poli yang aktif (status = '1') */
    public function scopeAktif($query)
    {
        return $query->where('status', '1');
    }
}