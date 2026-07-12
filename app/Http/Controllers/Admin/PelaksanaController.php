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

    public function edit(Pelaksana $pelaksana)
    {
        return view('admin.pelaksana.edit', compact('pelaksana'));
    }

    public function update(Request $request, Pelaksana $pelaksana)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $pelaksana->update($request->all());
        return redirect()->route('admin.pelaksana.index')->with('success', 'Data Pelaksana berhasil diperbarui.');
    }

    public function destroy(Pelaksana $pelaksana)
    {
        $pelaksana->delete();
        return redirect()->route('admin.pelaksana.index')->with('success', 'Data Pelaksana berhasil dihapus.');
    }
}
