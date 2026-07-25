<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsulanMasyarakat;
use App\Models\PekerjaanSda;
use App\Models\SuratPermohonan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UsulanMasyarakatController extends Controller
{
    public function index(Request $request)
    {
        $query = UsulanMasyarakat::with(['kecamatan', 'kelurahan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_pengusul', 'like', "%{$search}%")
                  ->orWhere('deskripsi_usulan', 'like', "%{$search}%");
        }

        $usulans = $query->latest()->paginate(10);
        return view('admin.usulan-masyarakat.index', compact('usulans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();
        return view('admin.usulan-masyarakat.create', compact('kecamatans', 'kelurahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'nullable|string',
            'nama_pengusul' => 'required|string',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi_usulan' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('usulan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;
        $validated['status'] = 'Menunggu';

        UsulanMasyarakat::create($validated);

        return redirect()->route('admin.usulan-masyarakat.index')->with('success', 'Usulan Masyarakat berhasil disimpan!');
    }

    public function show($id)
    {
        $usulan = UsulanMasyarakat::with(['kecamatan', 'kelurahan'])->findOrFail($id);
        return view('admin.usulan-masyarakat.show', compact('usulan'));
    }

    public function edit($id)
    {
        $usulan = UsulanMasyarakat::findOrFail($id);
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();
        return view('admin.usulan-masyarakat.edit', compact('usulan', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, $id)
    {
        $usulan = UsulanMasyarakat::findOrFail($id);
        
        $validated = $request->validate([
            'nomor_surat' => 'nullable|string',
            'nama_pengusul' => 'required|string',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi_usulan' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'status' => 'required|string',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $fotoPaths = [];
        if ($usulan->photo) {
            $fotoPaths = json_decode($usulan->photo, true) ?? [];
        }

        // Proses hapus foto
        if ($request->has('hapus_foto')) {
            $hapusIndexes = $request->input('hapus_foto');
            foreach ($hapusIndexes as $index) {
                if (isset($fotoPaths[$index])) {
                    Storage::delete($fotoPaths[$index]);
                    unset($fotoPaths[$index]);
                }
            }
            $fotoPaths = array_values($fotoPaths);
        }

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('usulan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $usulan->update($validated);

        return redirect()->route('admin.usulan-masyarakat.index')->with('success', 'Usulan Masyarakat berhasil diperbarui!');
    }

    public function terimaUsulan($id)
    {
        $usulan = UsulanMasyarakat::findOrFail($id);
        
        if ($usulan->status == 'Diproses' || $usulan->status == 'Selesai') {
            return redirect()->back()->withErrors(['Usulan ini sudah diproses atau selesai.']);
        }

        // Salin data ke tabel Pekerjaan SDA
        $pekerjaan = PekerjaanSda::create([
            'no_skpd' => $usulan->nomor_surat,
            'sumber_data' => 'Masyarakat',
            'rincian_sumber_data' => $usulan->nama_pengusul,
            'id_kecamatan' => $usulan->id_kecamatan,
            'id_kelurahan' => $usulan->id_kelurahan,
            'alamat' => $usulan->alamat,
            'deskripsi' => $usulan->deskripsi_usulan,
            'latitude' => $usulan->latitude,
            'longitude' => $usulan->longitude,
            'tgl_input' => date('Y-m-d'),
            'tahun_monev' => date('Y'),
        ]);

        // Update status usulan
        $usulan->update(['status' => 'Diproses']);

        // Auto-Generate Surat Permohonan
        SuratPermohonan::create([
            'id_pekerjaan_sda' => $pekerjaan->id,
            'nomor_surat' => $usulan->nomor_surat,
            'tanggal' => date('Y-m-d'),
            'status' => 'Menunggu',
            'dari' => 'Usulan Masyarakat (' . $usulan->nama_pengusul . ')',
            'id_kecamatan' => $pekerjaan->id_kecamatan,
            'id_kelurahan' => $pekerjaan->id_kelurahan,
            'lokasi' => $pekerjaan->alamat,
            'deskripsi' => $pekerjaan->deskripsi,
            'photo' => $usulan->photo, // Salin foto ke Surat Permohonan sebagai BEFORE
        ]);

        return redirect()->route('admin.pekerjaan-sda.edit', $pekerjaan->id)
                         ->with('success', 'Berhasil! Usulan masyarakat disalin menjadi Pekerjaan SDA dan draf Surat Permohonan otomatis dibuat.');
    }

    public function destroy($id)
    {
        $usulan = UsulanMasyarakat::findOrFail($id);
        
        if ($usulan->photo) {
            $fotos = json_decode($usulan->photo, true);
            if (is_array($fotos)) {
                foreach ($fotos as $f) {
                    Storage::delete($f);
                }
            }
        }
        
        $usulan->delete();

        return redirect()->route('admin.usulan-masyarakat.index')->with('success', 'Data berhasil dihapus!');
    }

    public function importEarsip()
    {
        try {
            $response = Http::timeout(10)->get('http://127.0.0.1:8000/api/reses');

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

                // Cek agar tidak duplikat
                $exists = UsulanMasyarakat::where('nomor_surat', $noSurat)
                    ->where('nama_pengusul', $asalSurat)
                    ->where('deskripsi_usulan', $perihal)
                    ->exists();

                if (!$exists) {
                    UsulanMasyarakat::create([
                        'nomor_surat' => $noSurat,
                        'nama_pengusul' => $asalSurat,
                        'deskripsi_usulan' => $perihal,
                        'status' => 'Menunggu',
                    ]);
                    $count++;
                }
            }

            return redirect()->route('admin.usulan-masyarakat.index')->with('success', "Berhasil menarik dan menyimpan $count data baru dari e-Arsip!");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan koneksi ke server e-Arsip: ' . $e->getMessage()]);
        }
    }
}
