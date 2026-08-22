@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Data Survei Reses</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.survei-reses.export-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary" style="background: #107c41; color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Ekspor Excel
            </a>
            <button type="button" onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-primary" style="background: #107c41; color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Impor Excel
            </button>
            <a href="{{ route('admin.survei-reses.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Inventarisasi Reses
            </a>
        </div>
    </div>
    <div style="padding: 20px; padding-bottom: 0;">
        <form method="GET" action="{{ route('admin.survei-reses.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Reses, Keluhan, atau Alamat..." style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; width: 300px; font-size: 13px;">
            
            <select name="status" onchange="this.form.submit()" style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; cursor: pointer; color: #374151;">
                <option value="">-- Semua Status --</option>
                <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                <option value="Disurvei" {{ request('status') == 'Disurvei' ? 'selected' : '' }}>Disurvei</option>
                <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" style="background: #111827; color: white; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 13px;">Cari / Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.survei-reses.index') }}" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #d1d5db; color: #374151; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center;">Reset</a>
            @endif
        </form>
    </div>
    
    <div style="padding: 20px; overflow-x: auto; padding-top: 0;">
        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">No</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Nama Dewan</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Wilayah</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Deskripsi Usulan</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Vol. / Est. Biaya</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Status</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($survei as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb; vertical-align: top;">
                    <td style="padding: 12px; color: #374151;">
                        @if($item->no_reses)
                            <strong>{{ $item->no_reses }}</strong>
                        @else
                            {{ ($survei->currentPage() - 1) * $survei->perPage() + $index + 1 }}
                        @endif
                    </td>
                    <td style="padding: 12px; color: #111827; font-weight: 500;">
                        {{ $item->dewan ? $item->dewan->nama : '-' }}<br>
                        <small style="color: #6b7280;">{{ \Carbon\Carbon::parse($item->tanggal_reses)->format('d M Y') }}</small>
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        Kec. {{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}<br>
                        Kel. {{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}<br>
                        <small style="color: #6b7280;">{{ Str::limit($item->alamat, 30) }}</small>
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        {{ Str::limit($item->keluhan, 50) }}
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        Vol: {{ $item->volume ?? 0 }} m&sup3;<br>
                        <small style="color: #6b7280;">Rp {{ number_format($item->estimasi_biaya ?? 0, 0, ',', '.') }}</small>
                    </td>
                    <td style="padding: 12px;">
                        @if($item->status == 'Baru')
                            <span style="background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Baru</span>
                        @elseif($item->status == 'Disurvei')
                            <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Disurvei</span>
                        @elseif($item->status == 'Diproses')
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Diproses</span>
                        @else
                            <span style="background: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Selesai</span>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #374151; display: flex; gap: 8px;">
                        <a href="{{ route('admin.survei-reses.show', $item->eid) }}" style="color: #1d4ed8; text-decoration: none; font-weight: 500; font-size: 13px;">Detail</a>
                        <a href="{{ route('admin.survei-reses.edit', $item->eid) }}" style="color: #059669; text-decoration: none; font-weight: 500; font-size: 13px;">Edit</a>
                        <form action="{{ route('admin.survei-reses.destroy', $item->eid) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; font-size: 13px; padding: 0;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada data Survei Reses.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($survei->hasPages())
    <div style="padding: 0 20px 20px 20px;">
        {{ $survei->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<!-- Modal Import Excel -->
<div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999;">
    <div style="background: white; padding: 24px; border-radius: 12px; width: 400px; max-width: 90%;">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 18px; color: #111827;">Impor Data Reses</h3>
        <form action="{{ route('admin.survei-reses.import-excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Pilih File Excel (.xlsx)</label>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background: white; border: 1px solid #d1d5db; color: #374151; padding: 8px 16px; border-radius: 8px; cursor: pointer;">Batal</button>
                <button type="submit" style="background: #107c41; border: none; color: white; padding: 8px 16px; border-radius: 8px; cursor: pointer;">Upload & Impor</button>
            </div>
        </form>
    </div>
</div>
@endsection
