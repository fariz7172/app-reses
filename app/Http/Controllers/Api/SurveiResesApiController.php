<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SurveiReses;
use App\Models\PekerjaanSda;
use App\Models\SuratPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SurveiResesApiController extends Controller
{
    /**
     * Convert uploaded image to WebP and save to storage
     * Returns storage path (e.g. "reses_photos/abc123.webp")
     */
    private function convertToWebP($file, string $folder = 'reses_photos'): string
    {
        $mime = $file->getMimeType();

        // Load source image
        $sourceImage = match (true) {
            str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($file->getRealPath()),
            str_contains($mime, 'png')  => imagecreatefrompng($file->getRealPath()),
            str_contains($mime, 'webp') => imagecreatefromwebp($file->getRealPath()),
            default                     => imagecreatefromjpeg($file->getRealPath()),
        };

        // Handle PNG transparency
        if (str_contains($mime, 'png')) {
            imagepalettetotruecolor($sourceImage);
            imagealphablending($sourceImage, true);
            imagesavealpha($sourceImage, true);
        }

        $filename  = $folder . '/' . Str::uuid() . '.webp';
        $fullPath  = storage_path('app/public/' . $filename);

        // Create folder if not exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        imagewebp($sourceImage, $fullPath, 82); // quality 82 (balance size vs quality)
        imagedestroy($sourceImage);

        return $filename;
    }

    /**
     * GET /api/survei-reses
     * Mengambil daftar seluruh Survei Reses (dengan filter & pagination)
     * Query params:
     *   search       string (cari di alamat, permintaan, keluhan, keterangan)
     *   status       string (Menunggu / Diproses / Selesai)
     *   id_dewan     int
     *   id_kecamatan int
     *   id_kelurahan int
     *   per_page     int (default 15, max 100)
     */
    public function index(Request $request)
    {
        $query = SurveiReses::with([
            'dewan:id,nama,komisi',
            'kecamatan:id,nama_kecamatan',
            'kelurahan:id,nama_kelurahan'
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('alamat', 'like', "%{$s}%")
                  ->orWhere('permintaan', 'like', "%{$s}%")
                  ->orWhere('keluhan', 'like', "%{$s}%")
                  ->orWhere('keterangan', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_dewan')) {
            $query->where('id_dewan', $request->id_dewan);
        }

        if ($request->filled('id_kecamatan')) {
            $query->where('id_kecamatan', $request->id_kecamatan);
        }

        if ($request->filled('id_kelurahan')) {
            $query->where('id_kelurahan', $request->id_kelurahan);
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $data = $query->latest('id')->paginate($perPage);

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar Survei Reses berhasil diambil.',
            'data'    => $data
        ]);
    }

    /**
     * GET /api/survei-reses/map
     */
    public function getMapData(Request $request)
    {
        $totalSemuaData = SurveiReses::count();
        $data = SurveiReses::query()->get(['id', 'no_reses', 'alamat', 'keluhan', 'permintaan', 'status', 'foto']);

        return response()->json([
            'status'           => 'success',
            'message'          => 'Data Survei Reses Dewan berhasil diambil.',
            'total_semua_data' => $totalSemuaData,
            'data'             => $data
        ]);
    }

    /**
     * GET /api/survei-reses/{id}
     */
    public function getDetail($id)
    {
        $survei = SurveiReses::with(['dewan', 'kecamatan', 'kelurahan', 'pekerjaanSda'])->find($id);

        if (!$survei) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Detail Survei Reses berhasil diambil.', 'data' => $survei]);
    }

    /**
     * POST /api/survei-reses
     * Content-Type: multipart/form-data  (agar bisa upload file)
     *
     * Fields:
     *   id_dewan*        int
     *   id_kecamatan*    int
     *   id_kelurahan*    int
     *   tanggal_reses*   date  (Y-m-d)
     *   alamat*          string
     *   permintaan*      string
     *   keterangan       string (nullable)
     *   status           string (default: Menunggu)
     *   panjang          numeric (nullable)
     *   lebar            numeric (nullable)
     *   tinggi           numeric (nullable)
     *   volume           numeric (nullable)
     *   estimasi_biaya   numeric (nullable)
     *   foto[]           file   (image/jpeg, image/png, image/webp) → otomatis dikonversi ke WebP
     */
    public function store(Request $request)
    {
        // Normalisasi string kosong ("") menjadi null agar input opsional (seperti id_dewan) benar-benar opsional
        $input = $request->all();
        $nullableFields = [
            'id_dewan', 'panjang', 'lebar', 'tinggi', 'volume', 'estimasi_biaya', 'keterangan', 'status'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $input) && is_string($input[$field]) && trim($input[$field]) === '') {
                $input[$field] = null;
            }
        }

        $rules = [
            'id_dewan'       => 'nullable|exists:dewans,id',
            'id_kecamatan'   => 'required|exists:kecamatans,id',
            'id_kelurahan'   => 'required|exists:kelurahans,id',
            'tanggal_reses'  => 'required|date',
            'alamat'         => 'required|string|max:500',
            'permintaan'     => 'required|string',
            'keterangan'     => 'nullable|string',
            'status'         => 'nullable|string|max:50',
            'panjang'        => 'nullable|numeric|min:0',
            'lebar'          => 'nullable|numeric|min:0',
            'tinggi'         => 'nullable|numeric|min:0',
            'volume'         => 'nullable|numeric|min:0',
            'estimasi_biaya' => 'nullable|numeric|min:0',
        ];

        // Validasi foto secara fleksibel (bisa array foto[], single foto, atau tidak ada foto)
        if ($request->hasFile('foto')) {
            $rawFoto = $request->file('foto');
            if (is_array($rawFoto)) {
                $rules['foto.*'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            } else {
                $rules['foto'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            }
        } else {
            $rules['foto'] = 'nullable';
        }

        $messages = [
            'id_dewan.exists' => 'Anggota dewan yang dipilih tidak valid atau tidak ditemukan.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['keluhan']  = $data['permintaan'];
        $data['status']   = $data['status'] ?? 'Menunggu';
        $data['id_dewan'] = !empty($data['id_dewan']) ? $data['id_dewan'] : null;

        // Upload & convert foto ke WebP (mendukung single file maupun multiple file)
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            $rawFiles = $request->file('foto');
            $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];

            foreach ($files as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    try {
                        $fotoPaths[] = $this->convertToWebP($file, 'reses_photos');
                    } catch (\Exception $e) {
                        // Fallback: simpan file aslinya jika GD gagal
                        $fotoPaths[] = $file->store('reses_photos', 'public');
                    }
                }
            }
        }
        $data['foto'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        try {
            $survei = SurveiReses::create($data);

            // Auto-generate Pekerjaan SDA
            $pekerjaan = PekerjaanSda::create([
                'id_survei_reses' => $survei->id,
                'id_dewan'        => $survei->id_dewan,
                'sumber_data'     => 'Reses',
                'id_kecamatan'    => $survei->id_kecamatan,
                'id_kelurahan'    => $survei->id_kelurahan,
                'alamat'          => $survei->alamat,
                'deskripsi'       => $survei->keluhan . ' / ' . $survei->permintaan,
                'tgl_input'       => date('Y-m-d'),
                'tahun_monev'     => date('Y'),
            ]);

            // Auto-generate Surat Permohonan
            $pengirim = $survei->dewan ? 'Dewan: ' . $survei->dewan->nama : 'Dewan / Reses';
            $surat = SuratPermohonan::create([
                'id_pekerjaan_sda' => $pekerjaan->id,
                'tanggal'          => date('Y-m-d'),
                'status'           => 'Menunggu',
                'dari'             => $pengirim,
                'id_kecamatan'     => $pekerjaan->id_kecamatan,
                'id_kelurahan'     => $pekerjaan->id_kelurahan,
                'lokasi'           => $pekerjaan->alamat,
                'deskripsi'        => $pekerjaan->deskripsi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Survei Reses berhasil disimpan. Pekerjaan SDA & Surat Permohonan otomatis dibuat.',
            'data'    => [
                'survei_reses'     => $survei->fresh(['dewan', 'kecamatan', 'kelurahan']),
                'pekerjaan_sda'    => $pekerjaan,
                'surat_permohonan' => $surat,
                'foto_tersimpan'   => $fotoPaths,
            ]
        ], 201);
    }

    /**
     * PUT/POST /api/survei-reses/{id}
     * Update data Survei Reses (bisa JSON via PUT, atau multipart/form-data via POST jika ada upload foto)
     */
    public function update(Request $request, $id)
    {
        $survei = SurveiReses::find($id);

        if (!$survei) {
            return response()->json(['status' => 'error', 'message' => 'Data Survei Reses tidak ditemukan.'], 404);
        }

        // Normalisasi string kosong ("") menjadi null
        $input = $request->all();
        $nullableFields = [
            'id_dewan', 'panjang', 'lebar', 'tinggi', 'volume', 'estimasi_biaya', 'keterangan', 'status'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $input) && is_string($input[$field]) && trim($input[$field]) === '') {
                $input[$field] = null;
            }
        }

        $rules = [
            'id_dewan'       => 'nullable|exists:dewans,id',
            'id_kecamatan'   => 'sometimes|required|exists:kecamatans,id',
            'id_kelurahan'   => 'sometimes|required|exists:kelurahans,id',
            'tanggal_reses'  => 'sometimes|required|date',
            'alamat'         => 'sometimes|required|string|max:500',
            'permintaan'     => 'sometimes|required|string',
            'keterangan'     => 'nullable|string',
            'status'         => 'nullable|string|max:50',
            'panjang'        => 'nullable|numeric|min:0',
            'lebar'          => 'nullable|numeric|min:0',
            'tinggi'         => 'nullable|numeric|min:0',
            'volume'         => 'nullable|numeric|min:0',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'hapus_foto'     => 'nullable|array',
        ];

        if ($request->hasFile('foto')) {
            $rawFoto = $request->file('foto');
            if (is_array($rawFoto)) {
                $rules['foto.*'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            } else {
                $rules['foto'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            }
        } else {
            $rules['foto'] = 'nullable';
        }

        $messages = [
            'id_dewan.exists' => 'Anggota dewan yang dipilih tidak valid atau tidak ditemukan.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (array_key_exists('id_dewan', $data)) {
            $data['id_dewan'] = !empty($data['id_dewan']) ? $data['id_dewan'] : null;
        }

        if (isset($data['permintaan'])) {
            $data['keluhan'] = $data['permintaan'];
        }

        // Kelola Foto Existing & Hapus Foto jika diminta
        $fotoPaths = $survei->foto ? json_decode($survei->foto, true) : [];
        if (!is_array($fotoPaths)) {
            $fotoPaths = [];
        }

        if ($request->has('hapus_foto')) {
            $hapusIndexes = $request->input('hapus_foto');
            if (is_string($hapusIndexes)) {
                $hapusIndexes = explode(',', $hapusIndexes);
            }
            rsort($hapusIndexes);
            foreach ($hapusIndexes as $idx) {
                $idx = (int) trim($idx);
                if (isset($fotoPaths[$idx])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoPaths[$idx]);
                    unset($fotoPaths[$idx]);
                }
            }
            $fotoPaths = array_values($fotoPaths);
        }

        // Upload & konversi foto baru ke WebP
        if ($request->hasFile('foto')) {
            $rawFiles = $request->file('foto');
            $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];

            foreach ($files as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    try {
                        $fotoPaths[] = $this->convertToWebP($file, 'reses_photos');
                    } catch (\Exception $e) {
                        $fotoPaths[] = $file->store('reses_photos', 'public');
                    }
                }
            }
        }
        $data['foto'] = count($fotoPaths) > 0 ? json_encode(array_values($fotoPaths)) : null;
        unset($data['hapus_foto']);

        try {
            $survei->update($data);

            // Sinkronisasi ke Pekerjaan SDA jika terhubung
            if ($survei->pekerjaanSda) {
                $updatePekerjaan = [
                    'id_dewan'     => $survei->id_dewan,
                    'id_kecamatan' => $survei->id_kecamatan,
                    'id_kelurahan' => $survei->id_kelurahan,
                    'alamat'       => $survei->alamat,
                    'deskripsi'    => $survei->keluhan . ' / ' . $survei->permintaan,
                ];
                $survei->pekerjaanSda->update($updatePekerjaan);

                // Sinkronisasi ke Surat Permohonan jika terhubung
                if ($survei->pekerjaanSda->suratPermohonan) {
                    $pengirim = $survei->dewan ? 'Dewan: ' . $survei->dewan->nama : 'Dewan / Reses';
                    $survei->pekerjaanSda->suratPermohonan->update([
                        'id_kecamatan' => $survei->id_kecamatan,
                        'id_kelurahan' => $survei->id_kelurahan,
                        'lokasi'       => $survei->alamat,
                        'deskripsi'    => $survei->keluhan . ' / ' . $survei->permintaan,
                        'dari'         => $pengirim,
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Survei Reses berhasil diperbarui.',
            'data'    => [
                'survei_reses'     => $survei->fresh(['dewan', 'kecamatan', 'kelurahan']),
                'foto_tersimpan'   => $fotoPaths,
            ]
        ]);
    }
}
