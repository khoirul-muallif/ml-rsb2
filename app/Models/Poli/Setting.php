<?php

namespace App\Models\Poli;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabel: setting
 * Koneksi: poli (database SIK)
 *
 * Kolom yang dipakai di anjungan:
 *   nama_instansi, alamat_instansi, kabupaten, propinsi, kontak, email, logo (BLOB)
 */
class Setting extends Model
{
    protected $connection = 'poli';
    protected $table      = 'setting';
    public    $timestamps = false;
    public    $incrementing = false;

    protected $fillable = [
        'nama_instansi',
        'alamat_instansi',
        'kabupaten',
        'propinsi',
        'kontak',
        'email',
        'logo',
    ];

    /**
     * Logo disimpan sebagai BLOB biner di DB.
     * Accessor ini mengubahnya langsung jadi data-URI base64
     * sehingga bisa langsung dipakai di <img src="...">
     */
    public function getLogoBase64Attribute(): string
    {
        if (empty($this->logo)) {
            return '';
        }
        return 'data:image/jpeg;base64,' . base64_encode($this->logo);
    }
}