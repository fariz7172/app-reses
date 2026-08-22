<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::orderBy('nama', 'asc')->get();
        return view('admin.vendor.index', compact('vendor'));
    }

    public function create()
    {
        return view('admin.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Vendor::create($request->all());
        return redirect()->route('admin.vendor.index')->with('success', 'Data Vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $vendor = Vendor::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $vendor->update($request->all());
        return redirect()->route('admin.vendor.index')->with('success', 'Data Vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendor.index')->with('success', 'Data Vendor berhasil dihapus.');
    }
}
