<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratPermohonan;
use Illuminate\Http\Request;

class SuratPermohonanApiController extends Controller
{
    /**
     * Dapatkan data Surat Permohonan / Usulan untuk keperluan mapping / GIS
     * Method: GET /api/surat-permohonan/map
     */
    public function getMapData(Request $request)
    {
        $query = SuratPermohonan::query();
        
        $totalSemuaData = SuratPermohonan::count();
        
        $query->whereNotNull('latitude')->whereNotNull('longitude');

        $data = $query->get(['id', 'nomor_surat', 'dari', 'lokasi', 'deskripsi', 'latitude', 'longitude', 'status', 'photo']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data peta Surat Permohonan berhasil diambil.',
            'total_semua_data' => $totalSemuaData,
            'total_data_dipeta' => $data->count(),
            'data' => $data
        ]);
    }

    /**
     * Dapatkan detail lengkap dari 1 Surat Permohonan
     * Method: GET /api/surat-permohonan/{id}
     */
    public function getDetail($id)
    {
        $surat = SuratPermohonan::with([
            'pekerjaanSda', 
            'kecamatan', 
            'kelurahan'
        ])->find($id);

        if (!$surat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Surat Permohonan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Surat Permohonan berhasil diambil.',
            'data' => $surat
        ]);
    }
}
