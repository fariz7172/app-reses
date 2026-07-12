@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Edit Data Pelaksana</h2>
        <a href="{{ route('admin.pelaksana.index') }}" class="btn btn-ghost">Batal</a>
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

        <form action="{{ route('admin.pelaksana.update', $pelaksana->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 20px; max-width: 500px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama *</label>
                <input type="text" name="nama" required value="{{ old('nama', $pelaksana->nama) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 20px; max-width: 500px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $pelaksana->nip) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 20px; max-width: 500px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $pelaksana->jabatan) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; max-width: 500px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
