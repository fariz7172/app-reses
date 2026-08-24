@extends('layouts.admin')

@section('content')
<div class="content-card" style="max-width: 600px; margin: 0 auto;">
    <div class="content-card-header">
        <h2 class="content-card-title">Edit Data Dewan</h2>
        <a href="{{ route('admin.master.dewan') }}" class="btn btn-ghost">Batal & Kembali</a>
    </div>
    <div style="padding: 24px;">
        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.master.dewan.update', $dewan->eid) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Dewan</label>
                <input type="text" name="nama" value="{{ old('nama', $dewan->nama) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Komisi DPRD</label>
                <input type="text" name="komisi" value="{{ old('komisi', $dewan->komisi) }}" placeholder="Contoh: Komisi A" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Fraksi</label>
                <select name="id_fraksi" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                    <option value="">-- Pilih Fraksi (Kosongkan jika tidak ada) --</option>
                    @foreach($fraksis as $f)
                        <option value="{{ $f->id }}" {{ (old('id_fraksi', $dewan->id_fraksi) == $f->id) ? 'selected' : '' }}>{{ $f->nama_fraksi }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Dapil (Daerah Wilayah)</label>
                <select name="id_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                    <option value="">-- Pilih Kecamatan / Dapil --</option>
                    @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}" {{ (old('id_kecamatan', $dewan->id_kecamatan) == $k->id) ? 'selected' : '' }}>{{ $k->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
