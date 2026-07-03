<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PekerjaanSda;
use App\Models\SurveiReses;
use App\Models\Dewan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class PekerjaanSdaController extends Controller
{
    public function index()
    {
        $pekerjaan = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan'])->latest()->get();
        return view('admin.pekerjaan-sda.index', compact('pekerjaan'));
    }

    public function create(Request $request)
    {
        $survei_id = $request->query('survei_id');
        $survei_terpilih = null;
        
        if ($survei_id) {
            $survei_terpilih = SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->find($survei_id);
        }

        // Ambil data survei reses yang belum di eskalasi
        $survei_reses_list = SurveiReses::whereDoesntHave('pekerjaanSda')->get();
        
        $dewans = Dewan::all();
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('admin.pekerjaan-sda.create', compact('survei_reses_list', 'survei_terpilih', 'dewans', 'kecamatans', 'kelurahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_survei_reses' => 'nullable|exists:survei_reses,id',
            'sumber_data' => 'required|string',
            'no_skpd' => 'nullable|string',
            'tahun_monev' => 'nullable|numeric',
            'kode_tracking' => 'nullable|string',
            'id_dewan' => 'nullable|exists:dewans,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'lingkup_kewenangan' => 'nullable|string',
            'kategori_pekerjaan' => 'nullable|string',
            'kategori_prioritas' => 'nullable|string',
            'volume_panjang' => 'nullable|string',
            'metode_pekerjaan' => 'nullable|string',
            'tgl_input' => 'nullable|date',
            'tgl_survei' => 'nullable|date',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'estimasi_tgl_realisasi' => 'nullable|date',
            'tahun_dikerjakan' => 'nullable|numeric',
            'progress' => 'nullable|numeric|min:0|max:100',
            'status_tindak_lanjut' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('pekerjaan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $pekerjaan = PekerjaanSda::create($validated);

        // Jika bersumber dari Reses, update status reses menjadi Diproses
        if ($pekerjaan->id_survei_reses) {
            $survei = SurveiReses::find($pekerjaan->id_survei_reses);
            if ($survei) {
                $survei->update(['status' => 'Diproses']);
            }
        }

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Pekerjaan SDA berhasil disimpan!');
    }

    public function show(string $id)
    {
        $pekerjaan = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'surveiReses', 'suratPermohonan'])->findOrFail($id);
        return view('admin.pekerjaan-sda.show', compact('pekerjaan'));
    }

    public function edit(string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        $dewans = Dewan::all();
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('admin.pekerjaan-sda.edit', compact('pekerjaan', 'dewans', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        
        $validated = $request->validate([
            'sumber_data' => 'required|string',
            'no_skpd' => 'nullable|string',
            'tahun_monev' => 'nullable|numeric',
            'kode_tracking' => 'nullable|string',
            'id_dewan' => 'nullable|exists:dewans,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'lingkup_kewenangan' => 'nullable|string',
            'kategori_pekerjaan' => 'nullable|string',
            'kategori_prioritas' => 'nullable|string',
            'volume_panjang' => 'nullable|string',
            'metode_pekerjaan' => 'nullable|string',
            'tgl_input' => 'nullable|date',
            'tgl_survei' => 'nullable|date',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'estimasi_tgl_realisasi' => 'nullable|date',
            'tahun_dikerjakan' => 'nullable|numeric',
            'progress' => 'nullable|numeric|min:0|max:100',
            'status_tindak_lanjut' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = $pekerjaan->photo ? json_decode($pekerjaan->photo, true) : [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('pekerjaan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $pekerjaan->update($validated);

        // Sinkronisasi status selesai ke reses jika progress 100
        if ($pekerjaan->id_survei_reses && $pekerjaan->progress == 100) {
            $survei = SurveiReses::find($pekerjaan->id_survei_reses);
            if ($survei) {
                $survei->update(['status' => 'Selesai']);
            }
        }

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Pekerjaan SDA berhasil diperbarui!');
    }

    public function mapView()
    {
        $pekerjaan = PekerjaanSda::whereNotNull('latitude')->whereNotNull('longitude')->get();
        return view('admin.pekerjaan-sda.map', compact('pekerjaan'));
    }

    public function destroy(string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        $pekerjaan->delete();
        return back()->with('success', 'Data Pekerjaan dihapus!');
    }
}
