<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tabel: dokter
 * PK   : kd_dokter (varchar 20)
 */
class Dokter extends Model
{
    protected $connection   = 'poli';
    protected $table        = 'dokter';
    protected $primaryKey   = 'kd_dokter';
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    protected $fillable = [
        'kd_dokter',
        'nm_dokter',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'gol_drh',
        'agama',
        'almt_tgl',
        'no_telp',
        'stts_nikah',
        'kd_sps',
        'alumni',
        'no_ijn_praktek',
        'status',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kd_dokter', 'kd_dokter');
    }

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'kd_dokter', 'kd_dokter');
    }

    public function antriPoli(): HasMany
    {
        return $this->hasMany(AntriPoli::class, 'kd_dokter', 'kd_dokter');
    }

    // ----------------------------------------------------------------
    // Scope
    // ----------------------------------------------------------------

    /** Hanya dokter aktif (status = '1') */
    public function scopeAktif($query)
    {
        return $query->where('status', '1');
    }
}