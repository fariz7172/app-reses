<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PekerjaanSda;
use Illuminate\Http\Request;

class PekerjaanSdaApiController extends Controller
{
    /**
     * Dapatkan data Pekerjaan SDA untuk keperluan mapping / GIS
     * Method: GET /api/pekerjaan-sda/map
     */
    public function getMapData(Request $request)
    {
        // Bisa ditambahkan filter jika diperlukan
        $query = PekerjaanSda::query();
        
        // Total semua data yang ada di database (tanpa filter koordinat)
        $totalSemuaData = PekerjaanSda::count();
        
        // Memastikan koordinat tidak kosong untuk ditampilkan di peta
        $query->whereNotNull('latitude')->whereNotNull('longitude');

        $data = $query->get(['id', 'no_skpd', 'alamat', 'deskripsi', 'latitude', 'longitude', 'progress', 'status_tindak_lanjut', 'sumber_data', 'photo']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data peta Pekerjaan SDA berhasil diambil.',
            'total_semua_data' => $totalSemuaData,
            'total_data_dipeta' => $data->count(),
            'data' => $data
        ]);
    }

    /**
     * Dapatkan detail lengkap dari 1 Pekerjaan SDA
     * Method: GET /api/pekerjaan-sda/{id}
     */
    public function getDetail($id)
    {
        $pekerjaan = PekerjaanSda::with([
            'surveiReses', 
            'dewan', 
            'kecamatan', 
            'kelurahan', 
            'pelaksana', 
            'vendor',
            'suratPermohonan'
        ])->find($id);

        if (!$pekerjaan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pekerjaan SDA tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Pekerjaan SDA berhasil diambil.',
            'data' => $pekerjaan
        ]);
    }
}
