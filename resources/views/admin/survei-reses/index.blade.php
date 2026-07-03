@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Data Survei Reses</h2>
        <a href="{{ route('admin.survei-reses.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Inventarisasi Reses
        </a>
    </div>
    <div style="padding: 20px; overflow-x: auto;">
        
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
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Permintaan / Keluhan</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Vol. / Est. Biaya</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Status</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($survei as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb; vertical-align: top;">
                    <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
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
                        {{ Str::limit($item->permintaan, 50) }}
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
                        <a href="{{ route('admin.survei-reses.show', $item->id) }}" style="color: #1d4ed8; text-decoration: none; font-weight: 500; font-size: 13px;">Detail</a>
                        <a href="{{ route('admin.survei-reses.edit', $item->id) }}" style="color: #059669; text-decoration: none; font-weight: 500; font-size: 13px;">Edit</a>
                        <form action="{{ route('admin.survei-reses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
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
</div>
@endsection
