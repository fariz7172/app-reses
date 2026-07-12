<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fraksi;
use Illuminate\Http\Request;

class FraksiController extends Controller
{
    public function index()
    {
        $fraksi = Fraksi::orderBy('nama_fraksi', 'asc')->get();
        return view('admin.fraksi.index', compact('fraksi'));
    }

    public function create()
    {
        return view('admin.fraksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fraksi' => 'required|string|max:255',
        ]);

        Fraksi::create($request->all());
        return redirect()->route('admin.fraksi.index')->with('success', 'Data Fraksi berhasil ditambahkan.');
    }

    public function edit(Fraksi $fraksi)
    {
        return view('admin.fraksi.edit', compact('fraksi'));
    }

    public function update(Request $request, Fraksi $fraksi)
    {
        $request->validate([
            'nama_fraksi' => 'required|string|max:255',
        ]);

        $fraksi->update($request->all());
        return redirect()->route('admin.fraksi.index')->with('success', 'Data Fraksi berhasil diperbarui.');
    }

    public function destroy(Fraksi $fraksi)
    {
        $fraksi->delete();
        return redirect()->route('admin.fraksi.index')->with('success', 'Data Fraksi berhasil dihapus.');
    }
}
