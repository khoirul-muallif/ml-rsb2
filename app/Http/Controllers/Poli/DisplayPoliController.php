<?php

namespace App\Http\Controllers\Poli;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Poli\Concerns\DecryptsPoliParams;
use App\Models\Poli\AntriPoli;
use App\Models\Poli\Dokter;
use App\Models\Poli\Jadwal;
use App\Models\Poli\Poliklinik;
use App\Models\Poli\RegPeriksa;
use App\Models\Poli\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisplayPoliController extends Controller
{
    use DecryptsPoliParams;

    // ----------------------------------------------------------------
    // Halaman utama display (blade)
    // ----------------------------------------------------------------

    public function index(Request $request): View
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);

        return view('anjungan.poli.display', [
            'kdPoli'    => $kdPoli,
            'kdDokter'  => $kdDokter,
            'encPoli'   => encrypt($kdPoli),
            'encDokter' => encrypt($kdDokter),
        ]);
    }

    // ----------------------------------------------------------------
    // API: Data antrian terkini
    // ----------------------------------------------------------------

    public function getAntrian(Request $request): JsonResponse
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);
        $namaHari = Jadwal::namaHariIni();

        // -- Info header ------------------------------------------
        $jadwal = Jadwal::on('poli')
            ->with(['dokter', 'poliklinik'])
            ->where('kd_dokter', $kdDokter)
            ->where('hari_kerja', $namaHari)
            ->first();

        // FIX: Cache setting agar tidak query DB setiap polling
        $setting = $this->getSetting();

        $info = [
            'nm_poli'      => $jadwal?->poliklinik?->nm_poli ?? '-',
            'nm_dokter'    => $jadwal?->dokter?->nm_dokter   ?? '-',
            'jam_praktek'  => $jadwal?->jam_praktek          ?? '-',   // pakai accessor model
            'keterangan'   => $jadwal?->keterangan           ?? '',
            'tgl_hari'     => now()->translatedFormat('d F Y'),
            'jam_sekarang' => now()->format('H:i'),
            'logo_base64'  => $setting?->logo_base64         ?? '',
        ];

        // -- ANTRIAN MASUK -----------------------------------------
        // FIX: Eager load relasi agar tidak terjadi N+1 query.
        // Sebelumnya: loop foreach dengan query per pasien.
        // Sekarang: 1 query antripoli + 1 eager load reg_periksa + pasien.
        $antriAktif = AntriPoli::on('poli')
            ->with(['regPeriksa.pasien', 'regPeriksa.poliklinik'])
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->where('status',    '1')
            ->get();

        $masuk = $antriAktif
            ->filter(fn($antri) => $antri->regPeriksa !== null)
            ->map(fn($antri) => [
                'no_antrian' => $antri->regPeriksa->nomor_antrian,
                'nm_pasien'  => $antri->regPeriksa->pasien?->nm_pasien    ?? '-',
                'nm_poli'    => $antri->regPeriksa->poliklinik?->nm_poli  ?? '-',
            ])
            ->values();

        // -- DAFTAR TUNGGU -----------------------------------------
        $tunggu = RegPeriksa::on('poli')
            ->with('pasien')
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->hariIni()
            ->menunggu()
            ->orderBy('no_reg')
            ->get()
            ->map(fn($reg) => [
                'no_antrian' => $reg->nomor_antrian,
                'nm_pasien'  => $reg->pasien?->nm_pasien ?? '-',
            ]);

        return response()->json([
            'info'   => $info,
            'masuk'  => $masuk,
            'tunggu' => $tunggu,
        ]);
    }

    // ----------------------------------------------------------------
    // API: Acknowledge — reset status='1' → '0' setelah suara bunyi
    // ----------------------------------------------------------------

    public function acknowledge(Request $request): JsonResponse
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);

        $updated = AntriPoli::resetStatus($kdPoli, $kdDokter);

        return response()->json(['success' => true, 'updated' => $updated]);
    }

    // ----------------------------------------------------------------
    // URL Generator
    // ----------------------------------------------------------------

    public function urlGenerator(): View
    {
        $poliList   = Poliklinik::on('poli')->aktif()->orderBy('nm_poli')->get();
        $dokterList = Dokter::on('poli')->aktif()->orderBy('nm_dokter')->get();

        return view('anjungan.poli.url-generator', compact('poliList', 'dokterList'));
    }

    // ----------------------------------------------------------------
    // Jadwal Praktek Dokter Hari Ini (Blade)
    // ----------------------------------------------------------------

    public function jadwal(Request $request): View
    {
        $namaHari = Jadwal::namaHariIni();
        $perPage  = in_array((int) $request->input('per_page', 10), [10, 20, 50])
                    ? (int) $request->input('per_page', 10) : 10;

        // FIX 1: Hapus ->with(['dokter','poliklinik']) yang redundan karena sudah di-join.
        // FIX 2: Ganti count() per baris dengan withCount subquery agar tidak N+1.
        //        Perlu relasi regPeriksa di model Jadwal (lihat model).
        $jadwalList = Jadwal::on('poli')
            ->withCount([
                'regPeriksa as jumlah_register' => fn($q) =>
                    $q->whereColumn('reg_periksa.kd_poli', 'jadwal.kd_poli')
                    ->whereDate('tgl_registrasi', today()),
            ])
            ->join('dokter',     'jadwal.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('poliklinik', 'jadwal.kd_poli',   '=', 'poliklinik.kd_poli')
            ->where('hari_kerja', $namaHari)
            ->orderBy('poliklinik.nm_poli')
            ->orderBy('jadwal.jam_mulai')
            ->select('jadwal.*', 'dokter.nm_dokter', 'poliklinik.nm_poli')
            ->paginate($perPage);
            // ->through(function ($item) {
            //     $encPoli   = encrypt($item->kd_poli);
            //     $encDokter = encrypt($item->kd_dokter);

            //     $item->url_display   = route('anjungan.poli.display',   ['poli' => $encPoli, 'dokter' => $encDokter]);
            //     $item->url_pemanggil = route('anjungan.poli.pemanggil', ['poli' => $encPoli, 'dokter' => $encDokter]);
            //     // FIX 3: Hapus format manual jam_praktek — sudah ada accessor di model Jadwal.

            //     return $item;
            // });

        $setting = $this->getSetting();

        return view('anjungan.poli.jadwal', [
            'jadwalList' => $jadwalList,
            'setting'    => $setting,
            'namaHari'   => $namaHari,
            'perPage'    => $perPage,
        ]);
    }

    // ----------------------------------------------------------------
    // API: Jadwal hari ini (AJAX)
    // ----------------------------------------------------------------

    public function getJadwal(): JsonResponse
    {
        $namaHari = Jadwal::namaHariIni();

        // FIX: Sama seperti jadwal() — hapus with() redundan, ganti count loop dengan withCount.
        $jadwalList = Jadwal::on('poli')
            ->withCount([
                'regPeriksa as jumlah_register' => fn($q) =>
                    $q->whereColumn('reg_periksa.kd_poli', 'jadwal.kd_poli')
                    ->whereDate('tgl_registrasi', today()),
            ])
            ->join('dokter',     'jadwal.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('poliklinik', 'jadwal.kd_poli',   '=', 'poliklinik.kd_poli')
            ->where('hari_kerja', $namaHari)
            ->orderBy('poliklinik.nm_poli')
            ->orderBy('jadwal.jam_mulai')
            ->select('jadwal.*', 'dokter.nm_dokter', 'poliklinik.nm_poli')
            ->get()
            ->map(function ($item) {
                $encPoli   = encrypt($item->kd_poli);
                $encDokter = encrypt($item->kd_dokter);

                return [
                    'nm_dokter'       => $item->nm_dokter,
                    'nm_poli'         => $item->nm_poli,
                    'jam_praktek'     => $item->jam_praktek,    // pakai accessor model
                    'jumlah_register' => $item->jumlah_register,
                    'url_display'     => route('anjungan.poli.display',   ['poli' => $encPoli, 'dokter' => $encDokter]),
                    'url_pemanggil'   => route('anjungan.poli.pemanggil', ['poli' => $encPoli, 'dokter' => $encDokter]),
                ];
            });

        return response()->json([
            'jadwal'       => $jadwalList,
            'jam_sekarang' => now()->format('H:i'),
            'tgl_hari'     => now()->format('d-M-Y'),
        ]);
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------

    /**
     * Ambil setting dari cache (10 menit).
     * Setting hampir tidak pernah berubah — tidak perlu query DB setiap polling.
     */
    private function getSetting(): ?Setting
    {
        return cache()->store('array')->remember('poli.setting', now()->addMinutes(10), fn() =>
            Setting::on('poli')->first()
        );
    }
}