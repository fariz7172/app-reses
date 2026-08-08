@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h2 class="content-card-title">Daftar Surat Permohonan & Usulan Masuk</h2>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('admin.surat-permohonan.import-earsip') }}" method="POST" onsubmit="return confirm('Tarik data usulan terbaru dari e-Arsip?');">
                @csrf
                <button type="submit" class="btn btn-primary" style="background: #1591DC; color: #fff; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Tarik Data e-Arsip
                </button>
            </form>
            <button type="button" onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-primary" style="background: #107c41; color: white; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Impor Excel
            </button>
            <a href="{{ route('admin.surat-permohonan.export-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary" style="background: #107c41; color: white; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Ekspor Excel
            </a>
            <a href="{{ route('admin.surat-permohonan.create') }}" class="btn btn-primary" style="background: #059669; color: #fff; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Manual
            </a>
        </div>
    </div>
    
    <div style="padding: 20px; padding-bottom: 0;">
        <form method="GET" action="{{ route('admin.surat-permohonan.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat, pengirim, atau deskripsi..." style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; width: 300px; font-size: 13px;">
            
            <select name="status" onchange="this.form.submit()" style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; cursor: pointer; color: #374151;">
                <option value="">-- Semua Status --</option>
                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses (Pekerjaan SDA)</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" style="background: #111827; color: white; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 13px;">Cari / Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.surat-permohonan.index') }}" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #d1d5db; color: #374151; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center;">Reset</a>
            @endif
        </form>
    </div>

    <div style="padding: 20px; overflow-x: auto; padding-top: 0;">
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
                    <td style="padding: 12px; color: #374151;">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}</td>
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
                    <td style="padding: 12px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        @if(in_array($item->status, ['Menunggu', 'Diajukan', 'Diterima']) && !$item->id_pekerjaan_sda)
                            @if(empty($item->id_kecamatan) || empty($item->id_kelurahan) || empty($item->lokasi))
                                <button type="button" onclick="showModalIncomplete({{ $item->id }})" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; margin-right: 4px;">
                                    Proses
                                </button>
                            @else
                                <form action="{{ route('admin.surat-permohonan.proses', $item->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Proses usulan/surat ini menjadi Pekerjaan SDA?')" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; margin-right: 4px;">
                                        Proses
                                    </button>
                                </form>
                            @endif
                        @endif
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
        
        <div style="margin-top: 20px; overflow-x: auto; padding-bottom: 10px;">
            {{ $surat->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Modal Peringatan Data Belum Lengkap -->
<div id="modalIncomplete" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:24px; border-radius:12px; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="width: 50px; height: 50px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 style="margin-top:0; color:#111827; margin-bottom: 10px;">Data Belum Lengkap!</h3>
        <p style="color:#4b5563; font-size:14px; margin-bottom: 8px;">Surat/Usulan ini belum memiliki data <b>Kecamatan, Kelurahan, atau Titik Koordinat Peta</b>.</p>
        <p style="color:#4b5563; font-size:14px; margin-bottom: 24px;">Harap lengkapi terlebih dahulu melalui menu Edit sebelum memprosesnya ke Pekerjaan SDA.</p>
        <div style="display:flex; justify-content:center; gap:12px;">
            <button onclick="document.getElementById('modalIncomplete').style.display='none'" style="padding:10px 16px; border:1px solid #d1d5db; background:white; color:#374151; border-radius:8px; cursor:pointer; font-weight:600;">Batal</button>
            <a id="btnLengkapiData" href="#" style="background:#1591DC; color:white; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:600;">Lengkapi Data (Edit)</a>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div id="importModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; justify-content: center; align-items: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 18px; color: #1e293b;">Impor Data Excel</h3>
        <form action="{{ route('admin.surat-permohonan.import-excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #475569;">Pilih File Excel (.xlsx, .xls)</label>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="padding: 8px 16px; background: #e2e8f0; color: #475569; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: #107c41; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">Impor Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function showModalIncomplete(id) {
    document.getElementById('modalIncomplete').style.display = 'flex';
    document.getElementById('btnLengkapiData').href = '/admin/surat-permohonan/' + id + '/edit?auto_proses=1';
}
</script>
@endsection
