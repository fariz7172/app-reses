@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="content-card-title">Daftar Pekerjaan SDA</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.pekerjaan-sda.export-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary" style="background: #107c41; color: white; padding: 10px 16px; font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16" style="display:inline; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Ekspor Excel
            </a>
            <a href="{{ route('admin.pekerjaan-sda.create') }}" class="btn btn-primary" style="padding: 10px 16px; font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16" style="display:inline; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Pekerjaan
            </a>
        </div>
    </div>

    {{-- ===== FILTER KARTU KATEGORI ===== --}}
    <div style="padding: 16px 20px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; border-bottom: 2px solid #e5e7eb;">

        {{-- Kartu: Semua --}}
        <a href="{{ route('admin.pekerjaan-sda.index') }}?tab=semua&{{ http_build_query(request()->except(['tab', 'page'])) }}"
           style="display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 12px; text-decoration: none; transition: all 0.2s; border: 2px solid;
                  {{ $tab === 'semua' ? 'background: #f0fdf4; border-color: #1F6F5F; box-shadow: 0 2px 8px rgba(31,111,95,0.15);' : 'background: #f9fafb; border-color: #e5e7eb;' }}">
            <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
                        {{ $tab === 'semua' ? 'background: #1F6F5F;' : 'background: #e5e7eb;' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $tab === 'semua' ? 'white' : '#6b7280' }}" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; line-height: 1; {{ $tab === 'semua' ? 'color: #1F6F5F;' : 'color: #374151;' }}">{{ $countAll }}</div>
                <div style="font-size: 12px; font-weight: 600; margin-top: 3px; {{ $tab === 'semua' ? 'color: #1F6F5F;' : 'color: #6b7280;' }}">Semua Pekerjaan</div>
            </div>
            @if($tab === 'semua')
                <div style="margin-left: auto; width: 8px; height: 8px; background: #1F6F5F; border-radius: 999px;"></div>
            @endif
        </a>

        {{-- Kartu: Hasil Reses --}}
        <a href="{{ route('admin.pekerjaan-sda.index') }}?tab=reses&{{ http_build_query(request()->except(['tab', 'page'])) }}"
           style="display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 12px; text-decoration: none; transition: all 0.2s; border: 2px solid;
                  {{ $tab === 'reses' ? 'background: #f5f3ff; border-color: #7c3aed; box-shadow: 0 2px 8px rgba(124,58,237,0.15);' : 'background: #f9fafb; border-color: #e5e7eb;' }}">
            <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
                        {{ $tab === 'reses' ? 'background: #7c3aed;' : 'background: #ede9fe;' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $tab === 'reses' ? 'white' : '#7c3aed' }}" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; line-height: 1; {{ $tab === 'reses' ? 'color: #7c3aed;' : 'color: #374151;' }}">{{ $countReses }}</div>
                <div style="font-size: 12px; font-weight: 600; margin-top: 3px; {{ $tab === 'reses' ? 'color: #7c3aed;' : 'color: #6b7280;' }}">🏛 Hasil Reses</div>
            </div>
            @if($tab === 'reses')
                <div style="margin-left: auto; width: 8px; height: 8px; background: #7c3aed; border-radius: 999px;"></div>
            @endif
        </a>

        {{-- Kartu: Aspirasi Masyarakat --}}
        <a href="{{ route('admin.pekerjaan-sda.index') }}?tab=masyarakat&{{ http_build_query(request()->except(['tab', 'page'])) }}"
           style="display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 12px; text-decoration: none; transition: all 0.2s; border: 2px solid;
                  {{ $tab === 'masyarakat' ? 'background: #eff6ff; border-color: #0369a1; box-shadow: 0 2px 8px rgba(3,105,161,0.15);' : 'background: #f9fafb; border-color: #e5e7eb;' }}">
            <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
                        {{ $tab === 'masyarakat' ? 'background: #0369a1;' : 'background: #dbeafe;' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $tab === 'masyarakat' ? 'white' : '#0369a1' }}" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; line-height: 1; {{ $tab === 'masyarakat' ? 'color: #0369a1;' : 'color: #374151;' }}">{{ $countMasyarakat }}</div>
                <div style="font-size: 12px; font-weight: 600; margin-top: 3px; {{ $tab === 'masyarakat' ? 'color: #0369a1;' : 'color: #6b7280;' }}">👥 Aspirasi Masyarakat</div>
            </div>
            @if($tab === 'masyarakat')
                <div style="margin-left: auto; width: 8px; height: 8px; background: #0369a1; border-radius: 999px;"></div>
            @endif
        </a>

    </div>
    {{-- ===== END FILTER KARTU KATEGORI ===== --}}




    <div style="padding: 20px; overflow-x: auto;">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('admin.pekerjaan-sda.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. reses, deskripsi, alamat..." style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; min-width: 260px; outline: none;">
            <select name="progress" style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; min-width: 180px; background: white; color: #374151;">
                <option value="">Semua Progress</option>
                <option value="100" {{ request('progress') == '100' ? 'selected' : '' }}>100% (Selesai)</option>
                <option value="<100" {{ request('progress') == '<100' ? 'selected' : '' }}>&lt;100% Perencanaan</option>
            </select>
            <button type="submit" style="background: #111827; color: white; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 13px;">Filter</button>
            @if(request('search') || request('progress'))
                <a href="{{ route('admin.pekerjaan-sda.index') }}?tab={{ $tab }}" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #d1d5db; color: #374151; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center;">Reset</a>
            @endif
        </form>

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

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Label Tab Aktif --}}
        @if($tab === 'reses')
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding: 10px 14px; background: #f5f3ff; border-left: 4px solid #7c3aed; border-radius: 0 8px 8px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#7c3aed" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                <span style="font-size: 13px; font-weight: 600; color: #7c3aed;">Menampilkan: Pekerjaan SDA dari Hasil Reses</span>
            </div>
        @elseif($tab === 'masyarakat')
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding: 10px 14px; background: #eff6ff; border-left: 4px solid #0369a1; border-radius: 0 8px 8px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#0369a1" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                <span style="font-size: 13px; font-weight: 600; color: #0369a1;">Menampilkan: Pekerjaan SDA dari Aspirasi Masyarakat</span>
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
                    <td style="padding: 12px; color: #374151;">{{ ($pekerjaan->currentPage() - 1) * $pekerjaan->perPage() + $index + 1 }}</td>
                    <td style="padding: 12px; color: #111827;">
                        @if($item->sumber_data === 'Hasil Reses')
                            <span style="background: #ede9fe; color: #7c3aed; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; display: inline-block; margin-bottom: 4px;">🏛 Reses</span>
                        @elseif($item->sumber_data === 'Masyarakat')
                            <span style="background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; display: inline-block; margin-bottom: 4px;">👥 Aspirasi</span>
                        @else
                            <span style="font-weight: 600;">{{ $item->sumber_data }}</span>
                        @endif
                        <br>
                        @if($item->no_skpd)
                            <small style="color: #4b5563; font-weight: 600;">Ref: {{ $item->no_skpd }}</small><br>
                        @endif
                        @if($item->kode_tracking)
                            <small style="color: #4b5563; font-weight: 600;">Tracking: {{ $item->kode_tracking }}</small><br>
                        @endif
                        @if($item->id_survei_reses)
                            <small style="color: #059669; background: #d1fae5; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">Reses #{{ $item->surveiReses ? ($item->surveiReses->no_reses ?? $item->id_survei_reses) : $item->id_survei_reses }}</small>
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
                        <a href="{{ route('admin.pekerjaan-sda.show', $item->eid) }}" style="color: #1d4ed8; text-decoration: none; font-weight: 500;">Detail</a>
                        <a href="{{ route('admin.pekerjaan-sda.edit', $item->eid) }}" style="color: #059669; text-decoration: none; font-weight: 500;">Proses</a>
                        @if($item->progress == 100)
                            <a href="{{ route('admin.pekerjaan-sda.cetak-bast', $item->eid) }}" target="_blank" style="color: #ea580c; text-decoration: none; font-weight: 500;">Cetak BAST</a>
                        @endif
                        <form action="{{ route('admin.pekerjaan-sda.destroy', $item->eid) }}" method="POST" onsubmit="return confirm('{{ $item->suratPermohonan ? 'Yakin menghapus Pekerjaan SDA ini? Surat/Usulan yang terkait akan dibatalkan prosesnya dan kembali berstatus Menunggu.' : 'Yakin hapus data Pekerjaan SDA ini secara permanen?' }}');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; padding: 0;">{{ $item->suratPermohonan ? 'Batal Proses' : 'Hapus' }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                        @if($tab === 'reses')
                            Belum ada Pekerjaan SDA dari Hasil Reses.
                        @elseif($tab === 'masyarakat')
                            Belum ada Pekerjaan SDA dari Aspirasi Masyarakat.
                        @else
                            Belum ada data Pekerjaan SDA.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px; overflow-x: auto; padding-bottom: 10px;">
            {{ $pekerjaan->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
