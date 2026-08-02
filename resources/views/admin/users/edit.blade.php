@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Edit Pengguna: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="text-decoration: none;">Batal & Kembali</a>
    </div>

    <div style="padding: 24px;">
        @if ($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Lengkap / Instansi</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Email / Username</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Role Akses</label>
                    <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #fff;">
                        <option value="Super Admin" {{ old('role', $user->role) == 'Super Admin' ? 'selected' : '' }}>Super Admin (Semua Akses)</option>
                        <option value="Kasubag" {{ old('role', $user->role) == 'Kasubag' ? 'selected' : '' }}>Kasubag (Semua Akses)</option>
                        <option value="Sudin" {{ old('role', $user->role) == 'Sudin' ? 'selected' : '' }}>Sudin (Semua Akses)</option>
                        <option value="Kecamatan" {{ old('role', $user->role) == 'Kecamatan' ? 'selected' : '' }}>Kecamatan (Terbatas: Survei & Usulan)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Password Baru <span style="font-weight:400; color:#9ca3af;">(Kosongkan jika tidak ingin mengubah password)</span></label>
                    <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">Update Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
