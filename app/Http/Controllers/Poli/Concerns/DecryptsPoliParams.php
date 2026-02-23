<?php

namespace App\Http\Controllers\Poli\Concerns;

use Illuminate\Http\Request;

/**
 * Trait: DecryptsPoliParams
 *
 * Menghindari duplikasi method decryptParams() di setiap controller poli.
 * Cukup `use DecryptsPoliParams` di controller yang membutuhkan.
 */
trait DecryptsPoliParams
{
    /**
     * Decrypt parameter poli & dokter dari query string atau request body.
     *
     * @return array{0: string, 1: string}  [$kdPoli, $kdDokter]
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  abort(400)
     */
    private function decryptParams(Request $request): array
    {
        try {
            $kdPoli   = decrypt($request->input('poli',   ''));
            $kdDokter = decrypt($request->input('dokter', ''));
        } catch (\Exception $e) {
            abort(400, 'Parameter tidak valid atau sudah kadaluarsa.');
        }

        if (empty($kdPoli) || empty($kdDokter)) {
            abort(400, 'Parameter poli dan dokter wajib diisi.');
        }

        return [$kdPoli, $kdDokter];
    }
}