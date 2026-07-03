<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratPermohonan;
use App\Models\PekerjaanSda;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class SuratPermohonanController extends Controller
{
    public function index()
    {
        $surat = SuratPermohonan::with(['pekerjaanSda', 'kecamatan', 'kelurahan'])->latest()->get();
        return view('admin.surat-permohonan.index', compact('surat'));
    }

    public function create(Request $request)
    {
        $pekerjaan_id = $request->query('pekerjaan_id');
        $pekerjaan_terpilih = null;
        
        if ($pekerjaan_id) {
            $pekerjaan_terpilih = PekerjaanSda::with(['kecamatan', 'kelurahan'])->find($pekerjaan_id);
        }

        $pekerjaan_list = PekerjaanSda::whereDoesntHave('suratPermohonan')->get();
        $kecamatans = Kecamatan::all();
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
            'detail_pemohon' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'hasil_survei' => 'nullable|string',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('surat_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        SuratPermohonan::create($validated);

        return redirect()->route('admin.surat-permohonan.index')->with('success', 'Surat Permohonan berhasil dibuat!');
    }

    public function show(string $id)
    {
        $surat = SuratPermohonan::with(['pekerjaanSda', 'kecamatan', 'kelurahan'])->findOrFail($id);
        return view('admin.surat-permohonan.show', compact('surat'));
    }

    public function edit(string $id)
    {
        $surat = SuratPermohonan::findOrFail($id);
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('admin.surat-permohonan.edit', compact('surat', 'kecamatans', 'kelurahans'));
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
            'detail_pemohon' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'hasil_survei' => 'nullable|string',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = $surat->photo ? json_decode($surat->photo, true) : [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('surat_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $surat->update($validated);

        return redirect()->route('admin.surat-permohonan.index')->with('success', 'Surat Permohonan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $surat = SuratPermohonan::findOrFail($id);
        $surat->delete();
        return back()->with('success', 'Surat Permohonan dihapus!');
    }
}
