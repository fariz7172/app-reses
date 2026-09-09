@extends('layouts.admin')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Detail Pekerjaan SDA</h2>
        @php
            $backUrl = url()->previous();
            if($backUrl == url()->current()) {
                $backUrl = route('admin.pekerjaan-sda.index');
            }
        @endphp
        <a href="{{ $backUrl }}" class="btn btn-ghost">Kembali ke Daftar</a>
    </div>

    <div style="padding: 24px; font-size: 14px; color: #374151;">
        
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Status & Tracking</h3>
                <div>
                    @if($pekerjaan->progress == 100)
                        <span style="background: #d1fae5; color: #059669; padding: 6px 12px; border-radius: 999px; font-weight: 600;">Selesai (100%)</span>
                    @elseif($pekerjaan->progress > 0)
                        <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 999px; font-weight: 600;">Sedang Berjalan ({{ $pekerjaan->progress }}%)</span>
                    @else
                        <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 999px; font-weight: 600;">Perencanaan (0%)</span>
                    @endif
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                <div>
                    <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Kode Tracking</span>
                    <strong style="color: #0f172a;">{{ $pekerjaan->kode_tracking ?? '-' }}</strong>
                </div>
                <div>
                    <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">No SKPD/UKPD</span>
                    <strong style="color: #0f172a;">{{ $pekerjaan->no_skpd ?? '-' }}</strong>
                </div>
                <div>
                    <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Tahun Monev</span>
                    <strong style="color: #0f172a;">{{ $pekerjaan->tahun_monev ?? '-' }}</strong>
                </div>
                <div>
                    <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Sumber Data</span>
                    <strong style="color: #0f172a;">{{ $pekerjaan->sumber_data }}</strong>
                    @if($pekerjaan->id_survei_reses)
                        <a href="{{ route('admin.survei-reses.show', $pekerjaan->id_survei_reses) }}" style="display: block; font-size: 12px; color: #059669; margin-top: 4px; text-decoration: none;">&rarr; Lihat Reses Terkait</a>
                    @endif
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Detail Lokasi & Deskripsi</h3>
                <table style="width: 100%; margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; width: 150px;">Kecamatan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->kecamatan ? $pekerjaan->kecamatan->nama_kecamatan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Kelurahan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->kelurahan ? $pekerjaan->kelurahan->nama_kelurahan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Alamat / Lokasi</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $pekerjaan->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Titik Kordinat</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $pekerjaan->latitude && $pekerjaan->longitude ? $pekerjaan->latitude . ', ' . $pekerjaan->longitude : 'Belum diatur' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Nama Pekerjaan</td>
                        <td style="padding: 8px 0; vertical-align: top; color: #0f172a; font-weight: 500;">: {{ $pekerjaan->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Nama Dewan (Ref)</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $pekerjaan->dewan ? $pekerjaan->dewan->nama : '-' }}</td>
                    </tr>
                </table>

                @if($pekerjaan->latitude && $pekerjaan->longitude)
                <div style="margin-top: 10px;">
                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px; border: 1px solid #d1d5db; z-index: 1;"></div>
                </div>
                @endif
            </div>

            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Spesifikasi Teknis</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; width: 150px;">Kategori Pekerjaan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->kategori_pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Lingkup Kewenangan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->lingkup_kewenangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Metode Pekerjaan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->metode_pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Volume / Panjang</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->volume_panjang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Tahun Dikerjakan</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->tahun_dikerjakan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Pelaksana</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->pelaksana ? $pekerjaan->pelaksana->nama : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Vendor (Perusahaan)</td>
                        <td style="padding: 8px 0;">: {{ $pekerjaan->vendor ? $pekerjaan->vendor->nama : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Galeri Foto Progress</h3>
            <p style="font-size: 11px; color: #64748b; margin-top: -10px; margin-bottom: 16px;">Dari data Survei Reses & Usulan Masyarakat</p>
            
            @php
                $fotosProgress = [];
                // Foto dari Pekerjaan SDA (After/Progress)
                if ($pekerjaan->photo) {
                    $arr = json_decode($pekerjaan->photo, true);
                    if (is_array($arr)) $fotosProgress = array_merge($fotosProgress, $arr);
                }
                // Foto dari Survei Reses (Before)
                if ($pekerjaan->surveiReses && $pekerjaan->surveiReses->foto) {
                    $arr = json_decode($pekerjaan->surveiReses->foto, true);
                    if (is_array($arr)) $fotosProgress = array_merge($fotosProgress, $arr);
                }
                // Foto dari Surat Permohonan (Before)
                if ($pekerjaan->suratPermohonan && $pekerjaan->suratPermohonan->photo) {
                    $arr = json_decode($pekerjaan->suratPermohonan->photo, true);
                    if (is_array($arr)) $fotosProgress = array_merge($fotosProgress, $arr);
                }
                // Hapus duplikat jika ada foto yang sama
                $fotosProgress = array_unique($fotosProgress);
            @endphp

            @if(is_array($fotosProgress) && count($fotosProgress) > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                    @foreach($fotosProgress as $fotoPath)
                        <div style="width: 200px; height: 150px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                            <img src="{{ asset('storage/'.str_replace('public/', '', $fotoPath)) }}" alt="Foto Progress" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 24px; background: #f9fafb; text-align: center; border-radius: 8px; color: #6b7280; border: 1px dashed #d1d5db;">
                    Belum ada foto yang diunggah.
                </div>
            @endif
        </div>

        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; margin-top: 32px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Perbandingan Hasil Kerja (Before & After)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- BEFORE -->
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #dc2626; margin-bottom: 4px; text-align: center;">KONDISI BEFORE (SEBELUM)</h4>
                @php 
                    $fotosBefore = [];
                    $sumberBefore = '';
                    $linkUploadBefore = '';
                    if ($pekerjaan->suratPermohonan && $pekerjaan->suratPermohonan->photo) {
                        $fotosBefore = json_decode($pekerjaan->suratPermohonan->photo, true) ?? [];
                        $sumberBefore = 'Surat Permohonan / Usulan Masyarakat';
                        $linkUploadBefore = route('admin.surat-permohonan.edit', $pekerjaan->suratPermohonan->eid);
                    } elseif ($pekerjaan->surveiReses && $pekerjaan->surveiReses->foto) {
                        $fotosBefore = json_decode($pekerjaan->surveiReses->foto, true) ?? [];
                        $sumberBefore = 'Survei Reses Anggota Dewan';
                        $linkUploadBefore = route('admin.survei-reses.edit', $pekerjaan->surveiReses->eid);
                    } else {
                        // Tentukan link upload berdasarkan sumber data
                        if ($pekerjaan->suratPermohonan) {
                            $linkUploadBefore = route('admin.surat-permohonan.edit', $pekerjaan->suratPermohonan->eid);
                        } elseif ($pekerjaan->surveiReses) {
                            $linkUploadBefore = route('admin.survei-reses.edit', $pekerjaan->surveiReses->eid);
                        }
                    }
                @endphp
                <p style="font-size: 11px; color: #64748b; text-align: center; margin-bottom: 12px;">Dari data {{ $sumberBefore ?: 'Awal (Before)' }}</p>

                @if(count($fotosBefore) > 0)
                    {{-- Ada foto BEFORE → tampilkan foto + badge status --}}
                    <div style="text-align: center; margin-bottom: 12px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Proses Sedang Berlangsung (30–70%)
                        </span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                        @foreach($fotosBefore as $fb)
                            <img src="{{ asset('storage/'.str_replace('public/', '', $fb)) }}" alt="Before" style="width: 100%; max-width: 200px; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #f87171;">
                        @endforeach
                    </div>
                @else
                    {{-- Tidak ada foto BEFORE → tampilkan peringatan dengan tombol upload --}}
                    <div style="padding: 20px 16px; text-align: center; background: #fff7ed; border: 1px dashed #fb923c; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f97316" width="32" height="32" style="margin: 0 auto 10px; display: block;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <p style="font-size: 13px; font-weight: 700; color: #c2410c; margin-bottom: 6px;">Foto Belum Ada!</p>
                        <p style="font-size: 11px; color: #7c3aed; margin-bottom: 14px;">Segera upload foto kondisi SEBELUM pada data<br><strong>Survei Reses</strong> atau <strong>Surat & Usulan Masyarakat</strong></p>
                        @if($linkUploadBefore)
                            <a href="{{ $linkUploadBefore }}" style="display: inline-flex; align-items: center; gap: 6px; background: #ea580c; color: white; padding: 7px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                Upload Foto Sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- AFTER -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #16a34a; margin-bottom: 4px; text-align: center;">KONDISI AFTER (SESUDAH)</h4>
                <p style="font-size: 11px; color: #64748b; text-align: center; margin-bottom: 12px;">Dari data Pekerjaan SDA</p>
                
                @php 
                    $fotosAfter = [];
                    if ($pekerjaan->photo) {
                        $fotosAfter = json_decode($pekerjaan->photo, true) ?? [];
                        $fotosAfter = array_values(array_diff($fotosAfter, $fotosBefore ?? []));
                    }
                @endphp

                @if(count($fotosAfter) > 0)
                    {{-- Ada foto AFTER → tampilkan foto + badge selesai --}}
                    <div style="text-align: center; margin-bottom: 12px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Proses Sudah 100% — Selesai!
                        </span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                        @foreach($fotosAfter as $fa)
                            <img src="{{ asset('storage/'.str_replace('public/', '', $fa)) }}" alt="After" style="width: 100%; max-width: 200px; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #4ade80;">
                        @endforeach
                    </div>
                @else
                    {{-- Tidak ada foto AFTER --}}
                    <div style="padding: 20px 16px; text-align: center; background: #f0fdf4; border: 1px dashed #86efac; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4ade80" width="32" height="32" style="margin: 0 auto 10px; display: block;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <p style="font-size: 13px; font-weight: 700; color: #16a34a; margin-bottom: 6px;">Belum Ada Foto Hasil</p>
                        <p style="font-size: 11px; color: #64748b; margin-bottom: 14px;">Upload foto kondisi SESUDAH pekerjaan selesai<br>melalui halaman Edit Pekerjaan SDA</p>
                        <a href="{{ route('admin.pekerjaan-sda.edit', $pekerjaan->eid) }}" style="display: inline-flex; align-items: center; gap: 6px; background: #16a34a; color: white; padding: 7px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            Upload Foto Hasil
                        </a>
                    </div>
                @endif
            </div>
        </div>


        <div style="margin-top: 40px; text-align: right;">
            <a href="{{ route('admin.pekerjaan-sda.edit', $pekerjaan->eid) }}" class="btn btn-primary" style="padding: 10px 20px;">Edit & Update Progress</a>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@if($pekerjaan->latitude && $pekerjaan->longitude)
<script>
    let lat = {{ $pekerjaan->latitude }};
    let lng = {{ $pekerjaan->longitude }};
    
    let map = L.map('map').setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("<b>Lokasi Pekerjaan:</b><br>{{ Str::limit($pekerjaan->deskripsi, 50) }}")
        .openPopup();
</script>
@endif

@endsection
