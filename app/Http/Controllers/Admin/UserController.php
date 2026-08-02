<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Hanya yang bukan Kecamatan yang boleh masuk
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }

        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }

        $user = User::findOrFail($id);
        
        // Mencegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
