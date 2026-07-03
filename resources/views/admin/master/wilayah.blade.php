@extends('layouts.admin')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

    <!-- Bagian Kecamatan -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Tambah Kecamatan Baru</h2>
            </div>
            <div style="padding: 20px;">
                @if(session('success'))
                    <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->has('nama_kecamatan'))
                    <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        {{ $errors->first('nama_kecamatan') }}
                    </div>
                @endif

                <form action="{{ route('admin.master.wilayah.kecamatan.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Kecamatan</label>
                        <input type="text" name="nama_kecamatan" required placeholder="Contoh: Pademangan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Simpan Kecamatan</button>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Daftar Kecamatan</h2>
            </div>
            <div style="padding: 20px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">No</th>
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">Kecamatan</th>
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">Jml Kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kecamatans as $index => $kec)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
                            <td style="padding: 12px; color: #111827; font-weight: 500;">{{ $kec->nama_kecamatan }}</td>
                            <td style="padding: 12px; color: #374151;">{{ $kec->kelurahans_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian Kelurahan -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Tambah Kelurahan Baru</h2>
            </div>
            <div style="padding: 20px;">
                @if($errors->has('nama_kelurahan') || $errors->has('id_kecamatan'))
                    <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        Silakan periksa kembali inputan Kelurahan Anda.
                    </div>
                @endif

                <form action="{{ route('admin.master.wilayah.kelurahan.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Pilih Kecamatan</label>
                        <select name="id_kecamatan" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Kelurahan</label>
                        <input type="text" name="nama_kelurahan" required placeholder="Contoh: Ancol" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; background: #1591DC;">Simpan Kelurahan</button>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Daftar Kelurahan</h2>
            </div>
            <div style="padding: 20px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">No</th>
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">Kelurahan</th>
                            <th style="padding: 12px; color: #6b7280; font-weight: 600;">Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelurahans as $index => $kel)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
                            <td style="padding: 12px; color: #111827; font-weight: 500;">{{ $kel->nama_kelurahan }}</td>
                            <td style="padding: 12px; color: #374151;">
                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">
                                    {{ $kel->kecamatan ? $kel->kecamatan->nama_kecamatan : '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
