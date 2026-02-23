<?php

namespace App\Http\Controllers\Poli;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Poli\Concerns\DecryptsPoliParams;
use App\Models\Poli\AntriPoli;
use App\Models\Poli\Jadwal;
use App\Models\Poli\RegPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PemanggilPoliController extends Controller
{
    use DecryptsPoliParams;

    public function index(Request $request): View
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);
        $namaHari = Jadwal::namaHariIni();

        $jadwal = Jadwal::on('poli')
            ->with(['dokter', 'poliklinik'])
            ->where('kd_dokter', $kdDokter)
            ->where('hari_kerja', $namaHari)
            ->first();

        return view('anjungan.poli.pemanggil', [
            'kdPoli'    => $kdPoli,
            'kdDokter'  => $kdDokter,
            'encPoli'   => encrypt($kdPoli),
            'encDokter' => encrypt($kdDokter),
            'jadwal'    => $jadwal,
        ]);
    }

    // ----------------------------------------------------------------
    // API: Daftar pasien
    // ----------------------------------------------------------------

    public function getDaftar(Request $request): JsonResponse
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);

        // MENUNGGU: belum ada di antripoli sama sekali
        $menunggu = RegPeriksa::on('poli')
            ->with('pasien')
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->hariIni()
            ->menunggu()
            ->orderBy('no_reg')
            ->get()
            ->map(fn($reg) => [
                'no_rawat'   => $reg->no_rawat,
                'no_antrian' => $reg->nomor_antrian,
                'nm_pasien'  => $reg->pasien?->nm_pasien ?? '-',
                'stts'       => $reg->stts,
            ]);

        // MASUK: sudah pernah dipanggil (ada di antripoli)
        // FIX: Ganti foreach + query per pasien → eager load sekaligus.
        // Sebelumnya: 1 query antripoli + N query reg_periksa + N query pasien.
        // Sekarang  : 1 query antripoli + 1 eager load reg_periksa.pasien = 2 query total.
        $antriList = AntriPoli::on('poli')
            ->with(['regPeriksa.pasien'])
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->get();

        $masuk = $antriList
            ->filter(fn($antri) => $antri->regPeriksa !== null)
            ->map(function ($antri) {
                $reg = $antri->regPeriksa;

                /**
                 * Tiga kemungkinan status tampil:
                 *   'dipanggil'      → antripoli.status = '1'   (sedang aktif dipanggil)
                 *   'menunggu_masuk' → antripoli.status = '0'   + reg_periksa.stts ≠ 'Sudah'
                 *   'sudah_masuk'    → antripoli.status = '0'   + reg_periksa.stts = 'Sudah'
                 */
                $statusTampil = match (true) {
                    $antri->status === '1'     => 'dipanggil',
                    $reg->stts     === 'Sudah' => 'sudah_masuk',
                    default                    => 'menunggu_masuk',
                };

                return [
                    'no_rawat'       => $reg->no_rawat,
                    'no_antrian'     => $reg->nomor_antrian,
                    'nm_pasien'      => $reg->pasien?->nm_pasien ?? '-',
                    'stts'           => $reg->stts,
                    'status_tampil'  => $statusTampil,
                    'baru_dipanggil' => ($antri->status === '1'),
                ];
            })
            ->sortBy(fn($p) => match ($p['status_tampil']) {
                'dipanggil'      => 0,
                'menunggu_masuk' => 1,
                default          => 2,
            })
            ->values();

        // Info pasien aktif (status='1') untuk box atas + tombol Panggil Ulang
        // FIX: Data sudah ada di $antriList — tidak perlu query tambahan ke DB.
        $sedangDipanggil = $antriList->firstWhere('status', '1');

        $infoDipanggil = null;
        if ($sedangDipanggil?->regPeriksa) {
            $reg = $sedangDipanggil->regPeriksa;
            $infoDipanggil = [
                'no_rawat'   => $reg->no_rawat,
                'no_antrian' => $reg->nomor_antrian,
                'nm_pasien'  => $reg->pasien?->nm_pasien ?? '-',
            ];
        }

        return response()->json([
            'menunggu'         => $menunggu,
            'masuk'            => $masuk,
            'sedang_dipanggil' => $sedangDipanggil?->no_rawat,
            'info_dipanggil'   => $infoDipanggil,
            'total_menunggu'   => $menunggu->count(),
            'total_masuk'      => $masuk->count(),
        ]);
    }

    // ----------------------------------------------------------------
    // API: Panggil pasien baru
    // ----------------------------------------------------------------

    public function panggil(Request $request): JsonResponse
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);

        $noRawat = $request->input('no_rawat');
        if (empty($noRawat)) {
            return response()->json(['success' => false, 'message' => 'no_rawat wajib diisi.'], 422);
        }

        $reg = RegPeriksa::on('poli')
            ->with('pasien')
            ->where('no_rawat',  $noRawat)
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->first();

        if (!$reg) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        // FIX: Bungkus dua operasi DB dalam transaction agar atomic.
        // Tanpa transaction, jika ada dua petugas menekan Panggil bersamaan,
        // kedua pasien bisa ter-set status='1' sekaligus.
        //
        // CATATAN: Transaction hanya bekerja jika tabel antripoli pakai InnoDB.
        // Jika masih MyISAM, migration ke InnoDB sangat direkomendasikan.
        DB::connection('poli')->transaction(function () use ($kdPoli, $kdDokter, $noRawat, $reg) {
            AntriPoli::on('poli')
                ->where('kd_poli',   $kdPoli)
                ->where('kd_dokter', $kdDokter)
                ->update(['status' => '0']);

            AntriPoli::on('poli')->updateOrInsert(
                ['no_rawat'  => $noRawat],
                ['kd_dokter' => $kdDokter, 'kd_poli' => $kdPoli, 'status' => '1', 'no_rawat' => $noRawat]
            );

            if (in_array($reg->stts, ['Belum', 'Berkas Terkirim'])) {
                $reg->update(['stts' => 'Berkas Diterima']);
            }
        });

        return response()->json([
            'success'    => true,
            'message'    => "Memanggil: {$reg->nomor_antrian}",
            'no_antrian' => $reg->nomor_antrian,
            'nm_pasien'  => $reg->pasien?->nm_pasien ?? '-',
        ]);
    }

    // ----------------------------------------------------------------
    // API: Panggil ULANG
    // ----------------------------------------------------------------

    public function panggilUlang(Request $request): JsonResponse
    {
        [$kdPoli, $kdDokter] = $this->decryptParams($request);

        $noRawat = $request->input('no_rawat');
        if (empty($noRawat)) {
            return response()->json(['success' => false, 'message' => 'no_rawat wajib diisi.'], 422);
        }

        $antri = AntriPoli::on('poli')
            ->where('no_rawat',  $noRawat)
            ->where('kd_poli',   $kdPoli)
            ->where('kd_dokter', $kdDokter)
            ->first();

        if (!$antri) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien belum dalam antrian aktif. Gunakan tombol Panggil.',
            ], 404);
        }

        // FIX: Sama seperti panggil() — bungkus dalam transaction.
        DB::connection('poli')->transaction(function () use ($kdPoli, $kdDokter, $antri) {
            AntriPoli::on('poli')
                ->where('kd_poli',   $kdPoli)
                ->where('kd_dokter', $kdDokter)
                ->update(['status' => '0']);

            $antri->update(['status' => '1']);
        });

        // Load reg setelah transaction selesai
        $reg = RegPeriksa::on('poli')
            ->with('pasien')
            ->where('no_rawat', $noRawat)
            ->first();

        return response()->json([
            'success'    => true,
            'message'    => "Panggil ulang: {$reg?->nomor_antrian}",
            'no_antrian' => $reg?->nomor_antrian,
            'nm_pasien'  => $reg?->pasien?->nm_pasien ?? '-',
        ]);
    }
}