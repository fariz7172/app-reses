<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PekerjaanSda;
use App\Models\SurveiReses;
use App\Models\SuratPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PekerjaanSdaApiController extends Controller
{
    /**
     * Convert uploaded image to WebP and save to storage
     * Returns storage path (e.g. "pekerjaan_photos/abc123.webp")
     */
    private function convertToWebP($file, string $folder = 'pekerjaan_photos'): string
    {
        $mime = $file->getMimeType();

        $sourceImage = match (true) {
            str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($file->getRealPath()),
            str_contains($mime, 'png')  => imagecreatefrompng($file->getRealPath()),
            str_contains($mime, 'webp') => imagecreatefromwebp($file->getRealPath()),
            default                     => imagecreatefromjpeg($file->getRealPath()),
        };

        if (str_contains($mime, 'png')) {
            imagepalettetotruecolor($sourceImage);
            imagealphablending($sourceImage, true);
            imagesavealpha($sourceImage, true);
        }

        $filename = $folder . '/' . Str::uuid() . '.webp';
        $fullPath = storage_path('app/public/' . $filename);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        imagewebp($sourceImage, $fullPath, 82);
        imagedestroy($sourceImage);

        return $filename;
    }

    /**
     * GET /api/pekerjaan-sda
     * Mengambil daftar seluruh Pekerjaan SDA (dengan filter & pagination)
     * Query params:
     *   search        string (cari di alamat, deskripsi, no_skpd, kode_tracking)
     *   sumber_data   string (Reses / Masyarakat / Musrenbang / dll)
     *   id_kecamatan  int
     *   id_kelurahan  int
     *   tahun_monev   int
     *   progress      string ("100", "<100", "0")
     *   per_page      int (default 15, max 100)
     */
    public function index(Request $request)
    {
        $query = PekerjaanSda::with([
            'kecamatan:id,nama_kecamatan',
            'kelurahan:id,nama_kelurahan',
            'dewan:id,nama',
            'pelaksana:id,nama,nip,jabatan'
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('alamat', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%")
                  ->orWhere('no_skpd', 'like', "%{$s}%")
                  ->orWhere('kode_tracking', 'like', "%{$s}%");
            });
        }

        if ($request->filled('sumber_data')) {
            $query->where('sumber_data', $request->sumber_data);
        }

        if ($request->filled('id_kecamatan')) {
            $query->where('id_kecamatan', $request->id_kecamatan);
        }

        if ($request->filled('id_kelurahan')) {
            $query->where('id_kelurahan', $request->id_kelurahan);
        }

        if ($request->filled('tahun_monev')) {
            $query->where('tahun_monev', $request->tahun_monev);
        }

        if ($request->filled('progress')) {
            if ($request->progress === '100') {
                $query->where('progress', 100);
            } elseif ($request->progress === '<100') {
                $query->where('progress', '<', 100);
            } elseif ($request->progress === '0') {
                $query->where('progress', 0);
            }
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $data = $query->latest('id')->paginate($perPage);

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar Pekerjaan SDA berhasil diambil.',
            'data'    => $data
        ]);
    }

    /**
     * GET /api/pekerjaan-sda/map
     */
    public function getMapData(Request $request)
    {
        $totalSemuaData = PekerjaanSda::count();
        $data = PekerjaanSda::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'no_skpd', 'alamat', 'deskripsi', 'latitude', 'longitude', 'progress', 'status_tindak_lanjut', 'sumber_data', 'photo']);

        return response()->json([
            'status'            => 'success',
            'message'           => 'Data peta Pekerjaan SDA berhasil diambil.',
            'total_semua_data'  => $totalSemuaData,
            'total_data_dipeta' => $data->count(),
            'data'              => $data
        ]);
    }

    /**
     * GET /api/pekerjaan-sda/{id}
     */
    public function getDetail($id)
    {
        $pekerjaan = PekerjaanSda::with([
            'surveiReses', 'dewan', 'kecamatan', 'kelurahan', 'pelaksana', 'vendor', 'suratPermohonan'
        ])->find($id);

        if (!$pekerjaan) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Detail Pekerjaan SDA berhasil diambil.', 'data' => $pekerjaan]);
    }

    /**
     * POST /api/pekerjaan-sda
     * Content-Type: multipart/form-data
     *
     * === FIELD LENGKAP ===
     * WAJIB:
     *   sumber_data*      string  ("Reses" | "Masyarakat")
     *   id_kecamatan*     int
     *   alamat*           string
     *   deskripsi*        string
     *
     * OPSIONAL (semua nullable):
     *   no_skpd           string   — Nomor SKPD / surat
     *   tahun_monev       int      — Tahun monev (default: tahun ini)
     *   tgl_input         date     — Tanggal input (default: hari ini)
     *   rincian_sumber_data string — Detail sumber (nama pengusul, dll)
     *   kode_tracking     string   — Kode tracking internal
     *   id_dewan          int      — FK ke tabel dewans
     *   id_kelurahan      int      — FK ke tabel kelurahans
     *   rt                string   — RT
     *   rw                string   — RW
     *   lingkup_kewenangan string  — Kewenangan (Kota/Provinsi/Pusat)
     *   kategori_pekerjaan string  — Jenis pekerjaan
     *   kategori_prioritas string  — Prioritas
     *   status_tindak_lanjut string
     *   tgl_survei        date
     *   volume_panjang    string   — Volume / panjang (mis: "100 m²")
     *   tahun_dikerjakan  int
     *   metode_pekerjaan  string   — Swakelola / Kontrak
     *   checklist_perencanaan string (JSON atau string)
     *   estimasi_tgl_realisasi date
     *   tgl_mulai         date
     *   tgl_selesai       date
     *   progress          int      — 0-100
     *   latitude          numeric  — Koordinat GPS
     *   longitude         numeric  — Koordinat GPS
     *   id_pelaksana      int      — FK ke tabel pelaksanas
     *   photo[]           file     — Foto (jpeg/png/webp) → otomatis dikonversi ke WebP
     */
    public function store(Request $request)
    {
        // Normalisasi string kosong ("") menjadi null agar input opsional (seperti id_dewan) benar-benar opsional
        $input = $request->all();
        $nullableFields = [
            'id_dewan', 'id_kelurahan', 'id_pelaksana', 'id_survei_reses',
            'kode_tracking', 'no_skpd', 'rt', 'rw', 'lingkup_kewenangan',
            'kategori_pekerjaan', 'kategori_prioritas', 'status_tindak_lanjut',
            'volume_panjang', 'metode_pekerjaan', 'checklist_perencanaan',
            'tgl_survei', 'tgl_mulai', 'tgl_selesai', 'estimasi_tgl_realisasi',
            'latitude', 'longitude'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $input) && is_string($input[$field]) && trim($input[$field]) === '') {
                $input[$field] = null;
            }
        }

        $rules = [
            // WAJIB
            'sumber_data'           => 'required|string|max:100',
            'id_kecamatan'          => 'required|exists:kecamatans,id',
            'alamat'                => 'required|string|max:500',
            'deskripsi'             => 'required|string',

            // OPSIONAL — Referensi & Identifikasi (Boleh kosong/tidak diisi)
            'no_skpd'               => 'nullable|string|max:100',
            'tahun_monev'           => 'nullable|integer|min:2000|max:2100',
            'tgl_input'             => 'nullable|date',
            'rincian_sumber_data'   => 'nullable|string|max:255',
            'kode_tracking'         => 'nullable|string|max:100|unique:pekerjaan_sdas,kode_tracking',
            'id_dewan'              => 'nullable|exists:dewans,id',
            'id_kelurahan'          => 'nullable|exists:kelurahans,id',
            'rt'                    => 'nullable|string|max:10',
            'rw'                    => 'nullable|string|max:10',

            // OPSIONAL — Klasifikasi
            'lingkup_kewenangan'    => 'nullable|string|max:100',
            'kategori_pekerjaan'    => 'nullable|string|max:100',
            'kategori_prioritas'    => 'nullable|string|max:100',
            'status_tindak_lanjut'  => 'nullable|string|max:100',

            // OPSIONAL — Waktu & Volume
            'tgl_survei'            => 'nullable|date',
            'volume_panjang'        => 'nullable|string|max:100',
            'tahun_dikerjakan'      => 'nullable|integer|min:2000|max:2100',
            'metode_pekerjaan'      => 'nullable|string|max:100',
            'checklist_perencanaan' => 'nullable|string',
            'estimasi_tgl_realisasi'=> 'nullable|date',
            'tgl_mulai'             => 'nullable|date',
            'tgl_selesai'           => 'nullable|date',
            'progress'              => 'nullable|integer|min:0|max:100',

            // OPSIONAL — Lokasi GPS
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',

            // OPSIONAL — Relasi
            'id_pelaksana'          => 'nullable|exists:pelaksanas,id',
            'id_survei_reses'       => 'nullable|exists:survei_reses,id',
        ];

        // Validasi foto secara fleksibel (bisa array photo[], single photo, atau tidak ada foto)
        if ($request->hasFile('photo')) {
            $rawPhoto = $request->file('photo');
            if (is_array($rawPhoto)) {
                $rules['photo.*'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            } else {
                $rules['photo'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            }
        } else {
            $rules['photo'] = 'nullable';
        }

        $messages = [
            'kode_tracking.unique' => 'Kode tracking ":input" sudah digunakan. Mohon gunakan kode tracking yang lain.',
            'id_dewan.exists'      => 'Anggota dewan yang dipilih tidak valid atau tidak ditemukan.',
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
        $data['tgl_input']   = $data['tgl_input'] ?? date('Y-m-d');
        $data['tahun_monev'] = $data['tahun_monev'] ?? (int) date('Y');
        $data['progress']    = $data['progress'] ?? 0;

        // Pastikan foreign keys bernilai null jika kosong
        $data['id_dewan']      = !empty($data['id_dewan']) ? $data['id_dewan'] : null;
        $data['id_kelurahan']  = !empty($data['id_kelurahan']) ? $data['id_kelurahan'] : null;
        $data['id_pelaksana']  = !empty($data['id_pelaksana']) ? $data['id_pelaksana'] : null;
        $data['id_survei_reses'] = !empty($data['id_survei_reses']) ? (int)$data['id_survei_reses'] : null;
        $data['kode_tracking'] = !empty($data['kode_tracking']) ? $data['kode_tracking'] : null;

        // Upload & konversi foto ke WebP (mendukung single file maupun multiple file)
        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            $rawFiles = $request->file('photo');
            $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];

            foreach ($files as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    try {
                        $fotoPaths[] = $this->convertToWebP($file, 'pekerjaan_photos');
                    } catch (\Exception $e) {
                        // Fallback: simpan aslinya jika GD tidak tersedia
                        $fotoPaths[] = $file->store('pekerjaan_photos', 'public');
                    }
                }
            }
        }
        $data['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        try {
            $pekerjaan = PekerjaanSda::create($data);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode tracking tersebut sudah pernah digunakan. Mohon gunakan kode tracking yang berbeda.',
                'errors'  => [
                    'kode_tracking' => ['Kode tracking sudah terdaftar di database.']
                ]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Pekerjaan SDA berhasil disimpan.',
            'data'    => $pekerjaan->fresh(['kecamatan', 'kelurahan', 'dewan', 'pelaksana']),
            'foto_tersimpan' => $fotoPaths,
        ], 201);
    }

    /**
     * PATCH /api/pekerjaan-sda/{id}/progress
     *
     * Body (JSON):
     * {
     *   "progress": 75,
     *   "status_tindak_lanjut": "Sedang Dikerjakan",
     *   "tgl_mulai": "2024-10-01",
     *   "tgl_selesai": "2024-12-31",
     *   "kode_tracking": "TRK-001"
     * }
     */
    public function updateProgress(Request $request, $id)
    {
        $pekerjaan = PekerjaanSda::find($id);

        if (!$pekerjaan) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'progress'             => 'required|integer|min:0|max:100',
            'status_tindak_lanjut' => 'nullable|string|max:100',
            'tgl_mulai'            => 'nullable|date',
            'tgl_selesai'          => 'nullable|date',
            'kode_tracking'        => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $pekerjaan->update($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Progress Pekerjaan SDA berhasil diperbarui.',
            'data'    => $pekerjaan->fresh()
        ]);
    }

    /**
     * PUT/POST /api/pekerjaan-sda/{id}
     * Update data Pekerjaan SDA (bisa JSON via PUT, atau multipart/form-data via POST jika ada upload foto)
     */
    public function update(Request $request, $id)
    {
        $pekerjaan = PekerjaanSda::find($id);

        if (!$pekerjaan) {
            return response()->json(['status' => 'error', 'message' => 'Data Pekerjaan SDA tidak ditemukan.'], 404);
        }

        // Normalisasi string kosong ("") menjadi null
        $input = $request->all();
        $nullableFields = [
            'id_dewan', 'id_kelurahan', 'id_pelaksana', 'id_survei_reses',
            'kode_tracking', 'no_skpd', 'rt', 'rw', 'lingkup_kewenangan',
            'kategori_pekerjaan', 'kategori_prioritas', 'status_tindak_lanjut',
            'volume_panjang', 'metode_pekerjaan', 'checklist_perencanaan',
            'tgl_survei', 'tgl_mulai', 'tgl_selesai', 'estimasi_tgl_realisasi',
            'latitude', 'longitude'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $input) && is_string($input[$field]) && trim($input[$field]) === '') {
                $input[$field] = null;
            }
        }

        $rules = [
            'sumber_data'           => 'sometimes|required|string|max:100',
            'id_kecamatan'          => 'sometimes|required|exists:kecamatans,id',
            'alamat'                => 'sometimes|required|string|max:500',
            'deskripsi'             => 'sometimes|required|string',
            'no_skpd'               => 'nullable|string|max:100',
            'tahun_monev'           => 'nullable|integer|min:2000|max:2100',
            'tgl_input'             => 'nullable|date',
            'rincian_sumber_data'   => 'nullable|string|max:255',
            'kode_tracking'         => 'nullable|string|max:100|unique:pekerjaan_sdas,kode_tracking,' . $pekerjaan->id,
            'id_dewan'              => 'nullable|exists:dewans,id',
            'id_kelurahan'          => 'nullable|exists:kelurahans,id',
            'rt'                    => 'nullable|string|max:10',
            'rw'                    => 'nullable|string|max:10',
            'lingkup_kewenangan'    => 'nullable|string|max:100',
            'kategori_pekerjaan'    => 'nullable|string|max:100',
            'kategori_prioritas'    => 'nullable|string|max:100',
            'status_tindak_lanjut'  => 'nullable|string|max:100',
            'tgl_survei'            => 'nullable|date',
            'volume_panjang'        => 'nullable|string|max:100',
            'tahun_dikerjakan'      => 'nullable|integer|min:2000|max:2100',
            'metode_pekerjaan'      => 'nullable|string|max:100',
            'checklist_perencanaan' => 'nullable|string',
            'estimasi_tgl_realisasi'=> 'nullable|date',
            'tgl_mulai'             => 'nullable|date',
            'tgl_selesai'           => 'nullable|date',
            'progress'              => 'nullable|integer|min:0|max:100',
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',
            'id_pelaksana'          => 'nullable|exists:pelaksanas,id',
            'id_survei_reses'       => 'nullable|exists:survei_reses,id',
            'hapus_foto'            => 'nullable|array',
        ];

        if ($request->hasFile('photo')) {
            $rawPhoto = $request->file('photo');
            if (is_array($rawPhoto)) {
                $rules['photo.*'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            } else {
                $rules['photo'] = 'file|mimes:jpeg,jpg,png,webp|max:20480';
            }
        } else {
            $rules['photo'] = 'nullable';
        }

        $messages = [
            'kode_tracking.unique' => 'Kode tracking ":input" sudah digunakan data lain. Mohon gunakan kode lain.',
            'id_dewan.exists'      => 'Anggota dewan yang dipilih tidak valid atau tidak ditemukan.',
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

        // Foreign keys safety
        if (array_key_exists('id_dewan', $data)) {
            $data['id_dewan'] = !empty($data['id_dewan']) ? $data['id_dewan'] : null;
        }
        if (array_key_exists('id_kelurahan', $data)) {
            $data['id_kelurahan'] = !empty($data['id_kelurahan']) ? $data['id_kelurahan'] : null;
        }
        if (array_key_exists('id_pelaksana', $data)) {
            $data['id_pelaksana'] = !empty($data['id_pelaksana']) ? $data['id_pelaksana'] : null;
        }
        if (array_key_exists('kode_tracking', $data)) {
            $data['kode_tracking'] = !empty($data['kode_tracking']) ? $data['kode_tracking'] : null;
        }

        // Kelola Foto Existing & Hapus Foto jika diminta
        $fotoPaths = $pekerjaan->photo ? json_decode($pekerjaan->photo, true) : [];
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
        if ($request->hasFile('photo')) {
            $rawFiles = $request->file('photo');
            $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];

            foreach ($files as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    try {
                        $fotoPaths[] = $this->convertToWebP($file, 'pekerjaan_photos');
                    } catch (\Exception $e) {
                        $fotoPaths[] = $file->store('pekerjaan_photos', 'public');
                    }
                }
            }
        }
        $data['photo'] = count($fotoPaths) > 0 ? json_encode(array_values($fotoPaths)) : null;
        unset($data['hapus_foto']);

        try {
            $pekerjaan->update($data);

            // Sinkronisasi dengan Surat Permohonan jika ada
            if ($pekerjaan->suratPermohonan) {
                $pengirim = $pekerjaan->sumber_data;
                if ($pekerjaan->sumber_data == 'Reses' && $pekerjaan->dewan) {
                    $pengirim = 'Dewan: ' . $pekerjaan->dewan->nama;
                } elseif ($pekerjaan->rincian_sumber_data) {
                    $pengirim .= ' (' . $pekerjaan->rincian_sumber_data . ')';
                }

                $updateSurat = [
                    'nomor_surat'  => $pekerjaan->no_skpd,
                    'dari'         => $pengirim,
                    'id_kecamatan' => $pekerjaan->id_kecamatan,
                    'id_kelurahan' => $pekerjaan->id_kelurahan,
                    'lokasi'       => $pekerjaan->alamat,
                    'deskripsi'    => $pekerjaan->deskripsi,
                ];
                if ($pekerjaan->progress == 100) {
                    $updateSurat['status'] = 'Selesai';
                }
                $pekerjaan->suratPermohonan->update($updateSurat);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Pekerjaan SDA berhasil diperbarui.',
            'data'    => $pekerjaan->fresh(['kecamatan', 'kelurahan', 'dewan', 'pelaksana', 'suratPermohonan']),
            'foto_tersimpan' => $fotoPaths,
        ]);
    }
}
