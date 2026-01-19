<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianLoket extends Model
{
    public $timestamps = false;
    protected $table = 'mlite_antrian_loket';
    protected $primaryKey = 'kd';

    protected $fillable = [
        'type',
        'noantrian',
        'no_rkm_medis',
        'postdate',
        'start_time',
        'end_time',
        'status',
        'loket'
    ];

    protected $casts = [
        'postdate' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    // Scopes untuk query
    public function scopeHariIni($query)
    {
        return $query->whereDate('postdate', today());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBelumDipanggil($query)
    {
        return $query->where('status', '0');
    }

    public function scopeDipanggil($query)
    {
        return $query->where('status', '1');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', '2');
    }

    // Helper methods
    public static function getNomorTerakhir($type, $tanggal = null)
    {
        $tanggal = $tanggal ?? today();
        
        $antrian = self::where('type', $type)
            ->whereDate('postdate', $tanggal)
            ->orderByDesc('noantrian')
            ->first();

        return $antrian ? (int)$antrian->noantrian : 0;
    }

    public static function getJumlahAntrian($type, $tanggal = null)
    {
        $tanggal = $tanggal ?? today();
        
        return self::where('type', $type)
            ->whereDate('postdate', $tanggal)
            ->count();
    }

    public static function getPrefixHuruf($type)
    {
        $prefix = [
            'Loket' => 'A',
            'CS' => 'B',
            'Apotek' => 'F',
            'IGD' => 'C'
        ];

        return $prefix[$type] ?? 'X';
    }

    public function getNomorAntrianLengkapAttribute()
    {
        $prefix = self::getPrefixHuruf($this->type);
        return $prefix . $this->noantrian;
    }

    // Relasi ke pasien (optional, sesuaikan dengan struktur database kamu)
    // public function pasien()
    // {
    //     return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    // }
}