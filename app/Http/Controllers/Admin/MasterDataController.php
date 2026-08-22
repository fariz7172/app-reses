<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.master.dewan');
    }

    public function dewan()
    {
        $dewans = \App\Models\Dewan::with(['fraksi', 'kecamatan'])->get();
        $fraksis = \App\Models\Fraksi::all();
        $kecamatans = \App\Models\Kecamatan::all();
        return view('admin.master.dewan', compact('dewans', 'fraksis', 'kecamatans'));
    }

    public function storeDewan(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'komisi' => 'nullable|string',
            'id_fraksi' => 'nullable|exists:fraksis,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
        ]);

        \App\Models\Dewan::create($validated);
        return back()->with('success', 'Data Dewan berhasil ditambahkan!');
    }

    public function editDewan($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $dewan = \App\Models\Dewan::findOrFail($id);
        $fraksis = \App\Models\Fraksi::all();
        $kecamatans = \App\Models\Kecamatan::all();
        return view('admin.master.dewan_edit', compact('dewan', 'fraksis', 'kecamatans'));
    }

    public function updateDewan(\Illuminate\Http\Request $request, $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $dewan = \App\Models\Dewan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string',
            'komisi' => 'nullable|string',
            'id_fraksi' => 'nullable|exists:fraksis,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
        ]);

        $dewan->update($validated);
        return redirect()->route('admin.master.dewan')->with('success', 'Data Dewan berhasil diperbarui!');
    }

    public function destroyDewan($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $dewan = \App\Models\Dewan::findOrFail($id);
        $dewan->delete();
        return back()->with('success', 'Data Dewan berhasil dihapus!');
    }

    public function wilayah()
    {
        $kecamatans = \App\Models\Kecamatan::withCount('kelurahans')->get();
        $kelurahans = \App\Models\Kelurahan::with('kecamatan')->get();
        return view('admin.master.wilayah', compact('kecamatans', 'kelurahans'));
    }

    public function storeKecamatan(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|unique:kecamatans,nama_kecamatan'
        ]);

        \App\Models\Kecamatan::create($validated);
        return back()->with('success', 'Kecamatan berhasil ditambahkan!');
    }

    public function storeKelurahan(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string'
        ]);

        \App\Models\Kelurahan::create($validated);
        return back()->with('success', 'Kelurahan berhasil ditambahkan!');
    }
}
