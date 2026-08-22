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
        if (auth()->check()) {
            $role = strtolower(auth()->user()->role);
            
            if ($role === 'kecamatan') {
                $namaKecamatan = trim(str_ireplace('Kecamatan', '', auth()->user()->name));
                $kecamatan = \App\Models\Kecamatan::where('nama_kecamatan', $namaKecamatan)->first();
                return $kecamatan ? $kecamatan->id : -1;
            }
            if (in_array($role, ['super admin', 'admin', 'sudin', 'kasudin', 'kasubag'])) {
                return null; // Akses ke semua wilayah
            }
            // Jika role lain mencoba akses data (misal Vendor tanpa izin), return ID invalid
            return -1;
        }
        return null;
    }
}
