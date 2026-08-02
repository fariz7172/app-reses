<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get Kecamatan ID if the authenticated user has 'Kecamatan' role.
     * Returns null for Super Admin/Kasubag/Sudin.
     */
    protected function getKecamatanId()
    {
        if (auth()->check() && auth()->user()->role === 'Kecamatan') {
            $namaKecamatan = trim(str_replace('Kecamatan', '', auth()->user()->name));
            $kecamatan = \App\Models\Kecamatan::where('nama_kecamatan', $namaKecamatan)->first();
            return $kecamatan ? $kecamatan->id : null;
        }
        return null;
    }
}
