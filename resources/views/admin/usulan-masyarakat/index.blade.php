@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Data Usulan Masyarakat</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form action="{{ route('admin.usulan-masyarakat.index') }}" method="GET" style="display:flex; gap:8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/deskripsi..." style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; width: 250px;">
                <button type="submit" class="btn btn-ghost">Cari</button>
            </form>
            <form action="{{ route('admin.usulan-masyarakat.import-earsip') }}" method="POST" style="display:inline;" onsubmit="return confirm('Tarik data reses/usulan terbaru dari e-Arsip?');">
                @csrf
                <button type="submit" class="btn btn-primary" style="background: #0f766e; border-color: #0f766e; display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Tarik Data e-Arsip
                </button>
            </form>
            <a href="{{ route('admin.usulan-masyarakat.create') }}" class="btn btn-primary">+ Usulan Baru</a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr>
                    <th style="padding: 12px 20px;">No</th>
                    <th style="padding: 12px 20px;">No Surat</th>
                    <th style="padding: 12px 20px;">Nama Pengusul</th>
                    <th style="padding: 12px 20px;">Deskripsi Usulan</th>
                    <th style="padding: 12px 20px;">Lokasi</th>
                    <th style="padding: 12px 20px;">Status</th>
                    <th style="padding: 12px 20px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usulans as $index => $u)
                    <tr>
                        <td style="padding: 12px 20px;">{{ $usulans->firstItem() + $index }}</td>
                        <td style="padding: 12px 20px;">{{ $u->nomor_surat ?: '-' }}</td>
                        <td style="padding: 12px 20px; font-weight: 600;">{{ $u->nama_pengusul }}</td>
                        <td style="padding: 12px 20px;">{{ Str::limit($u->deskripsi_usulan, 50) }}</td>
                        <td style="padding: 12px 20px;">
                            {{ $u->kelurahan ? $u->kelurahan->nama_kelurahan : '-' }}, 
                            {{ $u->kecamatan ? $u->kecamatan->nama_kecamatan : '-' }}
                        </td>
                        <td style="padding: 12px 20px;">
                            @if($u->status == 'Menunggu')
                                <span class="badge-warning">Menunggu</span>
                            @elseif($u->status == 'Diproses')
                                <span class="badge-info">Diproses</span>
                            @elseif($u->status == 'Selesai')
                                <span class="badge-success">Selesai</span>
                            @else
                                <span class="badge-danger">{{ $u->status }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px 20px;">
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('admin.usulan-masyarakat.show', $u->id) }}" class="btn btn-ghost" style="padding: 6px 10px;">Detail</a>
                                <a href="{{ route('admin.usulan-masyarakat.edit', $u->id) }}" class="btn btn-ghost" style="padding: 6px 10px;">Edit</a>
                                <form action="{{ route('admin.usulan-masyarakat.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus usulan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost" style="padding: 6px 10px; color: #dc2626; border-color: #fecaca;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">Tidak ada data usulan masyarakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 20px; border-top: 1px solid #f3f4f6;">
        {{ $usulans->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
