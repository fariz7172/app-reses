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
                    if ($pekerjaan->suratPermohonan && $pekerjaan->suratPermohonan->photo) {
                        $fotosBefore = json_decode($pekerjaan->suratPermohonan->photo, true) ?? [];
                        $sumberBefore = 'Surat Permohonan / Usulan Masyarakat';
                    } elseif ($pekerjaan->surveiReses && $pekerjaan->surveiReses->foto) {
                        $fotosBefore = json_decode($pekerjaan->surveiReses->foto, true) ?? [];
                        $sumberBefore = 'Survei Reses Anggota Dewan';
                    }
                @endphp
                <p style="font-size: 11px; color: #64748b; text-align: center; margin-bottom: 16px;">Dari data {{ $sumberBefore ?: 'Awal (Before)' }}</p>

                @if(count($fotosBefore) > 0)
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                        @foreach($fotosBefore as $fb)
                            <img src="{{ asset('storage/'.str_replace('public/', '', $fb)) }}" alt="Before" style="width: 100%; max-width: 200px; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #f87171;">
                        @endforeach
                    </div>
                @else
                    <div style="padding: 24px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px;">Belum ada foto KONDISI SEBELUM yang terkait</div>
                @endif
            </div>

            <!-- AFTER -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #16a34a; margin-bottom: 4px; text-align: center;">KONDISI AFTER (SESUDAH)</h4>
                <p style="font-size: 11px; color: #64748b; text-align: center; margin-bottom: 16px;">Dari data Pekerjaan SDA</p>
                
                @if($pekerjaan->photo)
                    @php 
                        $fotosAfter = json_decode($pekerjaan->photo, true) ?? []; 
                        $fotosAfter = array_diff($fotosAfter, $fotosBefore ?? []);
                    @endphp
                    @if(is_array($fotosAfter) && count($fotosAfter) > 0)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                            @foreach($fotosAfter as $fa)
                                <img src="{{ asset('storage/'.str_replace('public/', '', $fa)) }}" alt="After" style="width: 100%; max-width: 200px; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #4ade80;">
                            @endforeach
                        </div>
                    @else
                        <div style="padding: 24px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px;">Belum ada foto yang diunggah</div>
                    @endif
                @else
                    <div style="padding: 24px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px;">Belum ada foto Pekerjaan SDA</div>
                @endif
            </div>
        </div>

        <div style="margin-top: 40px; text-align: right;">
            <a href="{{ route('admin.pekerjaan-sda.edit', $pekerjaan->id) }}" class="btn btn-primary" style="padding: 10px 20px;">Edit & Update Progress</a>
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
