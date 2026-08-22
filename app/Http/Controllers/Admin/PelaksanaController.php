<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelaksana;
use Illuminate\Http\Request;

class PelaksanaController extends Controller
{
    public function index()
    {
        $pelaksana = Pelaksana::orderBy('nama', 'asc')->get();
        return view('admin.pelaksana.index', compact('pelaksana'));
    }

    public function create()
    {
        return view('admin.pelaksana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Pelaksana::create($request->all());
        return redirect()->route('admin.pelaksana.index')->with('success', 'Data Pelaksana berhasil ditambahkan.');
    }

    public function edit($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $pelaksana = Pelaksana::findOrFail($id);
        return view('admin.pelaksana.edit', compact('pelaksana'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $pelaksana = Pelaksana::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $pelaksana->update($request->all());
        return redirect()->route('admin.pelaksana.index')->with('success', 'Data Pelaksana berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $pelaksana = Pelaksana::findOrFail($id);
        $pelaksana->delete();
        return redirect()->route('admin.pelaksana.index')->with('success', 'Data Pelaksana berhasil dihapus.');
    }
}
