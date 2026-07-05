<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveiResesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $survei = \App\Models\SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->latest()->get();
        return view('admin.survei-reses.index', compact('survei'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dewans = \App\Models\Dewan::all();
        $kecamatans = \App\Models\Kecamatan::all();
        $kelurahans = \App\Models\Kelurahan::all(); // Alternatively, loaded via AJAX
        
        return view('admin.survei-reses.create', compact('dewans', 'kecamatans', 'kelurahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dewan' => 'required|exists:dewans,id',
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'id_kelurahan' => 'required|exists:kelurahans,id',
            'tanggal_reses' => 'required|date',
            'alamat' => 'required|string',
            'permintaan' => 'required|string',
            'keterangan' => 'nullable|string',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'status' => 'required|string',
            'foto.*' => 'nullable|image|max:2048' // Validasi untuk setiap foto (max 2MB)
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('reses_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['foto'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;
        
        // Simpan keluhan yang sementara kita ambil dari permintaan
        $validated['keluhan'] = $validated['permintaan'];

        \App\Models\SurveiReses::create($validated);

        return redirect()->route('admin.survei-reses.index')->with('success', 'Data Survei Reses berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $survei = \App\Models\SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->findOrFail($id);
        return view('admin.survei-reses.show', compact('survei'));
    }

    public function edit(string $id)
    {
        $survei = \App\Models\SurveiReses::findOrFail($id);
        $dewans = \App\Models\Dewan::all();
        $kecamatans = \App\Models\Kecamatan::all();
        $kelurahans = \App\Models\Kelurahan::all();
        
        return view('admin.survei-reses.edit', compact('survei', 'dewans', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, string $id)
    {
        $survei = \App\Models\SurveiReses::findOrFail($id);
        
        $validated = $request->validate([
            'id_dewan' => 'required|exists:dewans,id',
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'id_kelurahan' => 'required|exists:kelurahans,id',
            'tanggal_reses' => 'required|date',
            'alamat' => 'required|string',
            'permintaan' => 'required|string',
            'keterangan' => 'nullable|string',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'status' => 'required|string',
            'foto.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = $survei->foto ? json_decode($survei->foto, true) : [];
        
        if ($request->has('hapus_foto')) {
            $hapusIndexes = $request->input('hapus_foto');
            rsort($hapusIndexes);
            foreach ($hapusIndexes as $index) {
                if (isset($fotoPaths[$index])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoPaths[$index]);
                    unset($fotoPaths[$index]);
                }
            }
            $fotoPaths = array_values($fotoPaths);
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('reses_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['foto'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;
        $validated['keluhan'] = $validated['permintaan'];

        $survei->update($validated);

        return redirect()->route('admin.survei-reses.index')->with('success', 'Data Survei Reses berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $survei = \App\Models\SurveiReses::findOrFail($id);
        $survei->delete();
        return back()->with('success', 'Data Survei Reses berhasil dihapus!');
    }
}
