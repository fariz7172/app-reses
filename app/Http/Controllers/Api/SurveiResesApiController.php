<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SurveiReses;
use Illuminate\Http\Request;

class SurveiResesApiController extends Controller
{
    /**
     * Dapatkan data Survei Reses (Dewan) untuk keperluan mapping / GIS
     * Method: GET /api/survei-reses/map
     */
    public function getMapData(Request $request)
    {
        $query = SurveiReses::query();
        
        $totalSemuaData = SurveiReses::count();
        
        // Asumsi: Jika model SurveiReses tidak punya latitude/longitude secara terpisah
        // atau jika ada di database. Mari kita cek kolomnya, di model SurveiReses sebelumnya
        // tidak ada latitude/longitude yang terlihat di fillable, tapi jika ada, kita saring.
        // Berdasarkan skema umum, mungkin tidak ada. Namun kita akan return semua data.
        
        // Coba kita kembalikan seluruh data saja dengan field penting
        $data = $query->get(['id', 'no_reses', 'alamat', 'keluhan', 'permintaan', 'status', 'foto']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data Survei Reses Dewan berhasil diambil.',
            'total_semua_data' => $totalSemuaData,
            'data' => $data
        ]);
    }

    /**
     * Dapatkan detail lengkap dari 1 Survei Reses
     * Method: GET /api/survei-reses/{id}
     */
    public function getDetail($id)
    {
        $survei = SurveiReses::with([
            'dewan', 
            'kecamatan', 
            'kelurahan',
            'pekerjaanSda'
        ])->find($id);

        if (!$survei) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Survei Reses tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Survei Reses berhasil diambil.',
            'data' => $survei
        ]);
    }
}
