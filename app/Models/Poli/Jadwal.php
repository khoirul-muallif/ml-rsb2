<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tabel: jadwal
 * PK compound: (kd_dokter, hari_kerja, jam_mulai)
 *
 * hari_kerja ENUM: SENIN, SELASA, RABU, KAMIS, JUMAT, SABTU, AKHAD
 */
class Jadwal extends Model
{
    protected $connection   = 'poli';
    protected $table        = 'jadwal';
    public    $incrementing = false;
    public    $timestamps   = false;

    /**
     * PK-nya compound — nonaktifkan fitur PK tunggal Eloquent.
     * Query by kd_dokter + hari_kerja dilakukan via where() biasa.
     */
    protected $primaryKey = null;

    protected $fillable = [
        'kd_dokter',
        'hari_kerja',
        'jam_mulai',
        'jam_selesai',
        'kd_poli',
        'kuota',
        'keterangan',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function poliklinik(): BelongsTo
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    /**
     * Relasi ke reg_periksa.
     * Dibutuhkan oleh withCount('regPeriksa') di Display Poli Contro ller::jadwal()
     * dan getJadwal() untuk menghitung jumlah register hari ini secara efisien
     * tanpa N+1 query.
     */
    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'kd_dokter', 'kd_dokter');
                    // ->where('reg_periksa.kd_poli', $this->kd_poli);
        // Catatan: join dua kolom (kd_dokter + kd_poli) karena jadwal bisa
        // punya dokter yang praktek di beberapa poli berbeda.
    }

    // ----------------------------------------------------------------
    // Accessor: jam praktek sudah diformat "HH:MM - HH:MM"
    // Dipakai langsung di view/controller — tidak perlu format ulang manual.
    // ----------------------------------------------------------------

    public function getJamPraktekAttribute(): string
    {
        $mulai   = substr($this->jam_mulai   ?? '', 0, 5);
        $selesai = substr($this->jam_selesai ?? '', 0, 5);
        return "{$mulai} - {$selesai}";
    }

    // ----------------------------------------------------------------
    // Helper: mapping nama hari DB ↔ Carbon/PHP
    // ----------------------------------------------------------------

    /**
     * Ambil nilai enum hari_kerja yang sesuai dengan hari ini.
     * Dipakai di controller agar tidak ada duplikasi logika.
     */
    public static function namaHariIni(): string
    {
        $map = [
            'Sunday'    => 'AKHAD',
            'Monday'    => 'SENIN',
            'Tuesday'   => 'SELASA',
            'Wednesday' => 'RABU',
            'Thursday'  => 'KAMIS',
            'Friday'    => 'JUMAT',
            'Saturday'  => 'SABTU',
        ];

        return $map[now()->format('l')] ?? 'SENIN';
    }
}