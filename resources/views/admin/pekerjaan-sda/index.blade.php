@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="content-card-title">Daftar Pekerjaan SDA</h2>
        <div>
            <a href="{{ route('admin.pekerjaan-sda.create') }}" class="btn btn-primary" style="padding: 10px 16px; font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16" style="display:inline; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Pekerjaan
            </a>
        </div>
    </div>
    
    <div style="padding: 20px; overflow-x: auto;">
        @if ($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">No</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Sumber / Ref</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Pekerjaan / Lokasi</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Wilayah</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Progress</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Status</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pekerjaan as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb; vertical-align: top;">
                    <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; color: #111827;">
                        <span style="font-weight: 600;">{{ $item->sumber_data }}</span><br>
                        @if($item->no_skpd)
                            <small style="color: #4b5563; font-weight: 600;">Ref: {{ $item->no_skpd }}</small><br>
                        @endif
                        @if($item->id_survei_reses)
                            <small style="color: #059669; background: #d1fae5; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">Terkait Reses #{{ $item->id_survei_reses }}</small>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        <strong style="color: #111827;">{{ Str::limit($item->deskripsi, 40) }}</strong><br>
                        <small style="color: #6b7280;">{{ Str::limit($item->alamat, 40) }}</small>
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        Kec. {{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}<br>
                        Kel. {{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}
                    </td>
                    <td style="padding: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="flex-grow: 1; background: #e5e7eb; border-radius: 999px; height: 8px; overflow: hidden; width: 60px;">
                                <div style="width: {{ $item->progress }}%; height: 100%; background: {{ $item->progress == 100 ? '#059669' : '#3b82f6' }};"></div>
                            </div>
                            <span style="font-size: 12px; font-weight: 600; color: #374151;">{{ $item->progress }}%</span>
                        </div>
                    </td>
                    <td style="padding: 12px;">
                        @if($item->progress == 100)
                            <span style="background: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Selesai</span>
                        @elseif($item->progress > 0)
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Sedang Berjalan</span>
                        @else
                            <span style="background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Perencanaan</span>
                        @endif
                    </td>
                    <td style="padding: 12px; display: flex; gap: 8px;">
                        <a href="{{ route('admin.pekerjaan-sda.show', $item->id) }}" style="color: #1d4ed8; text-decoration: none; font-weight: 500;">Detail</a>
                        <a href="{{ route('admin.pekerjaan-sda.edit', $item->id) }}" style="color: #059669; text-decoration: none; font-weight: 500;">Edit</a>
                        @if($item->progress == 100)
                            <a href="{{ route('admin.pekerjaan-sda.cetak-bast', $item->id) }}" target="_blank" style="color: #ea580c; text-decoration: none; font-weight: 500;">Cetak BAST</a>
                        @endif
                        <form action="{{ route('admin.pekerjaan-sda.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; padding: 0;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada data Pekerjaan SDA.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
