<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianLoket extends Model
{
    public $timestamps = false;
    protected $table = 'ml_antrian_loket';
    protected $primaryKey = 'kd';

    protected $fillable = [
        'type',
        'noantrian',
        'no_rkm_medis',
        'postdate',
        'start_time',
        'end_time',
        'status',
        'loket',
        'category' // ✅ Tambahan untuk VIP/Reguler
    ];

    protected $casts = [
        'postdate' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    // ========================================
    // SCOPES
    // ========================================
    
    public function scopeHariIni($query)
    {
        return $query->whereDate('postdate', today());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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

    // ✅ NEW: Scope untuk category
    public function scopeReguler($query)
    {
        return $query->where('category', 'reguler');
    }

    public function scopeVIP($query)
    {
        return $query->where('category', 'vip');
    }

    // ========================================
    // STATIC METHODS
    // ========================================

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

    /**
     * ✅ FIXED: Get prefix dengan support VIP
     * Lebih baik pakai AntrianHelper, tapi tetap sediakan fallback
     */
    public static function getPrefixHuruf($type)
    {
        // Coba ambil dari config dulu
        $config = \App\Helpers\AntrianHelper::getByType($type);
        if ($config) {
            return $config['prefix'];
        }
        
        // Fallback manual
        $prefix = [
            'Loket' => 'A',
            'LoketVIP' => 'AV',
            'CS' => 'B',
            'CSVIP' => 'BV',
            'Apotek' => 'F',
            'IGD' => 'C'
        ];

        return $prefix[$type] ?? 'X';
    }

    /**
     * Get antrian terbaru yang sedang dipanggil (status = 1)
     * Digunakan untuk display/monitor TV
     */
    public static function getTerbaruDipanggil($type = null)
    {
        $query = self::where('status', '1')
            ->whereDate('postdate', today())
            ->orderByDesc('end_time')
            ->orderByDesc('kd');

        if ($type) {
            $query->where('type', $type);
        }

        return $query->first();
    }

    /**
     * Get antrian berikutnya yang belum dipanggil
     * Digunakan untuk operasi pemanggil
     */
    public static function getBerikutnya($type, $tanggal = null)
    {
        $tanggal = $tanggal ?? today();

        return self::where('type', $type)
            ->whereDate('postdate', $tanggal)
            ->where('status', '0')
            ->orderBy('noantrian')
            ->first();
    }

    /**
     * ✅ IMPROVED: Get summary dengan support semua type
     */
    public static function getSummary($tanggal = null)
    {
        $tanggal = $tanggal ?? today();
        
        $types = ['Loket', 'LoketVIP', 'CS', 'CSVIP', 'Apotek'];
        $summary = [];
        
        foreach ($types as $type) {
            $key = strtolower(str_replace('VIP', '_vip', $type));
            
            $summary[$key] = [
                'total' => self::where('type', $type)->whereDate('postdate', $tanggal)->count(),
                'selesai' => self::where('type', $type)->where('status', '2')->whereDate('postdate', $tanggal)->count(),
                'diproses' => self::where('type', $type)->where('status', '1')->whereDate('postdate', $tanggal)->count(),
                'menunggu' => self::where('type', $type)->where('status', '0')->whereDate('postdate', $tanggal)->count(),
            ];
        }
        
        return $summary;
    }

    // ========================================
    // ATTRIBUTES (Accessors)
    // ========================================

    /**
     * ✅ IMPROVED: Nomor antrian lengkap dengan prefix dari config
     */
    public function getNomorAntrianLengkapAttribute()
    {
        $prefix = self::getPrefixHuruf($this->type);
        return $prefix . $this->noantrian;
    }

    /**
     * Status label untuk display
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            '0' => 'Menunggu',
            '1' => 'Dipanggil',
            '2' => 'Selesai',
            '3' => 'Tidak Hadir'
        ];

        return $status[$this->status] ?? 'Unknown';
    }

    /**
     * ✅ NEW: Get category label
     */
    public function getCategoryLabelAttribute()
    {
        return $this->category === 'vip' ? 'VIP' : 'Reguler';
    }

    /**
     * ✅ NEW: Check if VIP
     */
    public function getIsVipAttribute()
    {
        return $this->category === 'vip';
    }

    // ========================================
    // RELATIONSHIPS (Optional)
    // ========================================
    
    // Uncomment jika ada relasi ke pasien
    // public function pasien()
    // {
    //     return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    // }
}