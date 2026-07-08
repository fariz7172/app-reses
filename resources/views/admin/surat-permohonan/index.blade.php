@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="content-card-title">Daftar Surat Permohonan (Auto-Generated)</h2>
        <div>
        </div>
    </div>
    
    <div style="padding: 20px; overflow-x: auto;">
        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">No Surat</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Tanggal</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Dari (Pengirim)</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Pekerjaan SDA Ref</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Wilayah</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Status</th>
                    <th style="padding: 12px; color: #6b7280; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surat as $item)
                <tr style="border-bottom: 1px solid #e5e7eb; vertical-align: top;">
                    <td style="padding: 12px; color: #111827; font-weight: 600;">{{ $item->nomor_surat }}</td>
                    <td style="padding: 12px; color: #374151;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td style="padding: 12px; color: #374151;">{{ $item->dari }}</td>
                    <td style="padding: 12px; color: #374151;">
                        @if($item->id_pekerjaan_sda)
                            <a href="{{ route('admin.pekerjaan-sda.show', $item->id_pekerjaan_sda) }}" style="color: #059669; text-decoration: none; font-weight: 500;">Ya (Lihat Ref)</a>
                        @else
                            <span style="color: #9ca3af;">Tidak Ada</span>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #374151;">
                        Kec. {{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}<br>
                        Kel. {{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}
                    </td>
                    <td style="padding: 12px;">
                        @if($item->status == 'Selesai' || $item->status == 'Disetujui')
                            <span style="background: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">{{ $item->status }}</span>
                        @elseif($item->status == 'Ditolak')
                            <span style="background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Ditolak</span>
                        @else
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px; display: flex; gap: 8px;">
                        <a href="{{ route('admin.surat-permohonan.show', $item->id) }}" style="color: #1d4ed8; text-decoration: none; font-weight: 500;">Cetak</a>
                        <a href="{{ route('admin.surat-permohonan.edit', $item->id) }}" style="color: #059669; text-decoration: none; font-weight: 500;">Edit</a>
                        <form action="{{ route('admin.surat-permohonan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus surat ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; padding: 0;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada Surat Permohonan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
