@extends('layouts.admin')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">

    <!-- Bagian Form Input -->
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">Tambah Dewan Baru</h2>
        </div>
        <div style="padding: 20px;">
            @if(session('success'))
                <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.master.dewan.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Dewan</label>
                    <input type="text" name="nama" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Komisi DPRD</label>
                    <input type="text" name="komisi" placeholder="Contoh: Komisi A" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Fraksi</label>
                    <select name="id_fraksi" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                        <option value="">-- Pilih Fraksi (Kosongkan jika tidak ada) --</option>
                        @foreach($fraksis as $f)
                            <option value="{{ $f->id }}">{{ $f->nama_fraksi }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Dapil (Daerah Wilayah)</label>
                    <select name="id_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                        <option value="">-- Pilih Kecamatan / Dapil --</option>
                        @foreach($kecamatans as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Simpan Data</button>
            </form>
        </div>
    </div>

    <!-- Bagian Tabel Data -->
    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">Daftar Anggota Dewan</h2>
        </div>
        <div style="padding: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">No</th>
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">Nama Dewan</th>
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">Komisi</th>
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">Fraksi</th>
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">Dapil</th>
                        <th style="padding: 12px; color: #6b7280; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dewans as $index => $dewan)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; color: #111827; font-weight: 500;">{{ $dewan->nama }}</td>
                        <td style="padding: 12px; color: #374151;">{{ $dewan->komisi ?? '-' }}</td>
                        <td style="padding: 12px; color: #374151;">
                            @if($dewan->fraksi)
                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">{{ $dewan->fraksi->nama_fraksi }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td style="padding: 12px; color: #374151;">{{ $dewan->kecamatan ? $dewan->kecamatan->nama_kecamatan : '-' }}</td>
                        <td style="padding: 12px; color: #374151; display: flex; gap: 8px;">
                            <a href="{{ route('admin.master.dewan.edit', $dewan->id) }}" style="color: #059669; text-decoration: none; font-weight: 500;">Edit</a>
                            
                            <form action="{{ route('admin.master.dewan.destroy', $dewan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dewan ini?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; font-size: 14px; padding: 0;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada data Anggota Dewan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
