<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratPermohonan;
use App\Models\PekerjaanSda;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Exports\SuratPermohonanExport;
use App\Imports\SuratPermohonanImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SuratPermohonanController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratPermohonan::with(['pekerjaanSda', 'kecamatan', 'kelurahan']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('dari', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        $surat = $query->orderBy('id', 'desc')->paginate(10);
        $surat->appends($request->all());
        
        return view('admin.surat-permohonan.index', compact('surat'));
    }

    public function exportExcel(Request $request)
    {
        $query = SuratPermohonan::with(['pekerjaanSda', 'kecamatan', 'kelurahan']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('dari', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        $surat = $query->latest()->get(); // Ambil semua data tanpa pagination
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SuratPermohonanExport($surat), 
            'Data_Surat_Usulan_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function create(Request $request)
    {
        $pekerjaan_id = $request->query('pekerjaan_id');
        $pekerjaan_terpilih = null;
        
        if ($pekerjaan_id) {
            $pekerjaan_terpilih = PekerjaanSda::with(['kecamatan', 'kelurahan'])->find($pekerjaan_id);
        }

        $kecamatanId = $this->getKecamatanId();

        $pekerjaanQuery = PekerjaanSda::whereDoesntHave('suratPermohonan');
        if ($kecamatanId) {
            $pekerjaanQuery->where('id_kecamatan', $kecamatanId);
            $kecamatans = Kecamatan::where('id', $kecamatanId)->get();
        } else {
            $kecamatans = Kecamatan::all();
        }
        
        $pekerjaan_list = $pekerjaanQuery->get();
        $kelurahans = Kelurahan::all();

        return view('admin.surat-permohonan.create', compact('pekerjaan_list', 'pekerjaan_terpilih', 'kecamatans', 'kelurahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pekerjaan_sda' => 'nullable|exists:pekerjaan_sdas,id',
            'tanggal' => 'required|date',
            'nomor_surat' => 'required|string',
            'dari' => 'required|string',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'detail_pemohon' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'hasil_survei' => 'nullable|string',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'photo.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:20480'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('surat_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $surat = SuratPermohonan::create($validated);

        // Sync nomor_surat ke no_skpd di Pekerjaan SDA
        if ($surat->id_pekerjaan_sda && $surat->nomor_surat) {
            $pekerjaan = PekerjaanSda::find($surat->id_pekerjaan_sda);
            if ($pekerjaan) {
                $pekerjaan->update(['no_skpd' => $surat->nomor_surat]);
            }
        }

        return redirect()->route('admin.surat-permohonan.index')->with('success', 'Surat Permohonan / Usulan berhasil disimpan!');
    }

    public function show(string $id)
    {
        $surat = SuratPermohonan::with(['pekerjaanSda', 'kecamatan', 'kelurahan'])->findOrFail($id);
        return view('admin.surat-permohonan.show', compact('surat'));
    }

    public function edit(string $id)
    {
        $surat = SuratPermohonan::findOrFail($id);
        
        $kecamatanId = $this->getKecamatanId();
        
        if ($kecamatanId && $surat->id_kecamatan != $kecamatanId) {
            return redirect()->route('admin.surat-permohonan.index')->with('error', 'Akses ditolak.');
        }

        $pekerjaanQuery = PekerjaanSda::whereDoesntHave('suratPermohonan');
        if ($kecamatanId) {
            $pekerjaanQuery->where('id_kecamatan', $kecamatanId);
            $kecamatans = Kecamatan::where('id', $kecamatanId)->get();
        } else {
            $kecamatans = Kecamatan::all();
        }

        // Tampilkan pekerjaan yang belum terpakai ATAU pekerjaan yang sudah terkait dengan surat ini
        $pekerjaan_list = $pekerjaanQuery->orWhere('id', $surat->id_pekerjaan_sda)->get();
        
        $kelurahans = Kelurahan::all();
        
        return view('admin.surat-permohonan.edit', compact('surat', 'pekerjaan_list', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, string $id)
    {
        $surat = SuratPermohonan::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nomor_surat' => 'required|string',
            'dari' => 'required|string',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'detail_pemohon' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'hasil_survei' => 'nullable|string',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'photo.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:20480'
        ]);

        $fotoPaths = [];
        if ($surat->photo) {
            $fotoPaths = json_decode($surat->photo, true) ?? [];
        }

        // Proses hapus foto
        if ($request->has('hapus_foto')) {
            $hapusIndexes = $request->input('hapus_foto');
            foreach ($hapusIndexes as $index) {
                if (isset($fotoPaths[$index])) {
                    Storage::disk('public')->delete($fotoPaths[$index]);
                    unset($fotoPaths[$index]);
                }
            }
            $fotoPaths = array_values($fotoPaths);
        }

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('surat_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $surat->update($validated);

        // Sync nomor_surat ke no_skpd di Pekerjaan SDA
        if ($surat->id_pekerjaan_sda && $surat->nomor_surat) {
            $pekerjaan = PekerjaanSda::find($surat->id_pekerjaan_sda);
            if ($pekerjaan) {
                $pekerjaan->update(['no_skpd' => $surat->nomor_surat]);
            }
        }

        if ($request->has('auto_proses') && $request->auto_proses == 1) {
            return $this->prosesPekerjaan($surat->id);
        }

        return redirect()->route('admin.surat-permohonan.index')->with('success', 'Surat Permohonan / Usulan berhasil diperbarui!');
    }

    public function prosesPekerjaan($id)
    {
        $surat = SuratPermohonan::findOrFail($id);
        
        if ($surat->status == 'Diproses' || $surat->id_pekerjaan_sda != null) {
            return redirect()->back()->withErrors(['Surat Permohonan / Usulan ini sudah diproses dan memiliki Pekerjaan SDA terkait.']);
        }

        // Salin data ke tabel Pekerjaan SDA
        $pekerjaan = PekerjaanSda::create([
            'no_skpd' => $surat->nomor_surat,
            'sumber_data' => 'Masyarakat',
            'rincian_sumber_data' => $surat->dari,
            'id_kecamatan' => $surat->id_kecamatan,
            'id_kelurahan' => $surat->id_kelurahan,
            'alamat' => $surat->lokasi,
            'deskripsi' => $surat->deskripsi,
            'latitude' => $surat->latitude,
            'longitude' => $surat->longitude,
            'tgl_input' => date('Y-m-d'),
            'tahun_monev' => date('Y'),
        ]);

        // Update status surat & tautkan dengan PekerjaanSda
        $surat->update([
            'status' => 'Diproses',
            'id_pekerjaan_sda' => $pekerjaan->id
        ]);

        return redirect()->route('admin.pekerjaan-sda.edit', $pekerjaan->id)
                         ->with('success', 'Berhasil! Usulan/Surat disalin menjadi Pekerjaan SDA baru.');
    }

    public function importEarsip()
    {
        try {
            $apiUrl = env('EARSIP_API_URL', 'https://e-arsip.farizahmad.com/api/reses');
            $response = Http::timeout(10)->get($apiUrl);

            if (!$response->successful() || $response->json('status') !== 'success') {
                return redirect()->back()->withErrors(['Gagal mengambil data dari endpoint e-Arsip (HTTP ' . $response->status() . ').']);
            }

            $data = $response->json('data');
            if (!is_array($data) || count($data) === 0) {
                return redirect()->back()->withErrors(['Data dari e-Arsip kosong.']);
            }

            $count = 0;
            foreach ($data as $item) {
                $noSurat = $item['no_surat'] ?? null;
                $asalSurat = $item['asal_surat'] ?? 'Tanpa Pengirim';
                $perihal = $item['perihal'] ?? '-';

                // Cek agar tidak duplikat (skip jika nomor_surat sudah ada)
                if (empty($noSurat)) {
                    continue; // Jika nomor surat kosong, lewati
                }

                $exists = SuratPermohonan::where('nomor_surat', $noSurat)->exists();

                if (!$exists) {
                    SuratPermohonan::create([
                        'nomor_surat' => $noSurat,
                        'dari' => $asalSurat,
                        'deskripsi' => $perihal,
                        'tanggal' => date('Y-m-d'),
                        'status' => 'Menunggu',
                    ]);
                    $count++;
                }
            }

            return redirect()->route('admin.surat-permohonan.index')->with('success', "Berhasil menarik dan menyimpan $count data usulan baru dari e-Arsip!");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan koneksi ke server e-Arsip: ' . $e->getMessage()]);
        }
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SuratPermohonanImport, $request->file('file_excel'));
            return redirect()->route('admin.surat-permohonan.index')->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        if (!in_array(auth()->user()->role, ['Super Admin', 'Sudin'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data secara permanen.');
        }

        $surat = SuratPermohonan::findOrFail($id);
        
        if ($surat->photo) {
            $fotos = json_decode($surat->photo, true);
            if (is_array($fotos)) {
                foreach ($fotos as $f) {
                    Storage::disk('public')->delete($f);
                }
            }
        }

        $surat->delete();
        return back()->with('success', 'Surat Permohonan / Usulan dihapus!');
    }
}
