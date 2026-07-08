@extends('layouts.admin')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Detail Usulan Masyarakat</h2>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.usulan-masyarakat.index') }}" class="btn btn-ghost">Kembali</a>
            <a href="{{ route('admin.usulan-masyarakat.edit', $usulan->id) }}" class="btn btn-primary" style="background: #eab308; color: #fff;">Edit Usulan</a>
            @if($usulan->status == 'Menunggu' || $usulan->status == 'Ditolak')
                <form action="{{ route('admin.usulan-masyarakat.terima', $usulan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyalin Usulan ini menjadi Pekerjaan SDA?');">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="background: #059669; border-color: #059669;">&#10003; Terima & Jadikan Pekerjaan SDA</button>
                </form>
            @endif
        </div>
    </div>

    <div style="padding: 24px;">

        @if ($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Informasi Pengusul & Status</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Nama Pengusul</span>
                        <strong style="color: #0f172a;">{{ $usulan->nama_pengusul }}</strong>
                    </div>
                    <div>
                        <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Status Usulan</span>
                        @if($usulan->status == 'Menunggu')
                            <span class="badge-warning" style="padding: 4px 10px;">Menunggu</span>
                        @elseif($usulan->status == 'Diproses')
                            <span class="badge-info" style="padding: 4px 10px;">Diproses</span>
                        @elseif($usulan->status == 'Selesai')
                            <span class="badge-success" style="padding: 4px 10px;">Selesai</span>
                        @else
                            <span class="badge-danger" style="padding: 4px 10px;">{{ $usulan->status }}</span>
                        @endif
                    </div>
                    <div>
                        <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">Tanggal Diusulkan</span>
                        <strong style="color: #0f172a;">{{ $usulan->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>

            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Lokasi Usulan</h3>
                <table style="width: 100%; margin-bottom: 10px;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: 600; width: 120px; font-size: 13px;">Kecamatan</td>
                        <td style="padding: 6px 0; font-size: 13px;">: {{ $usulan->kecamatan ? $usulan->kecamatan->nama_kecamatan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 600; font-size: 13px;">Kelurahan</td>
                        <td style="padding: 6px 0; font-size: 13px;">: {{ $usulan->kelurahan ? $usulan->kelurahan->nama_kelurahan : '-' }}</td>
                    </tr>
                </table>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                    <span style="display: block; font-size: 11px; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Alamat Lengkap</span>
                    <p style="font-size: 14px; color: #334155; margin: 0; line-height: 1.5;">{{ $usulan->alamat ?: 'Tidak ada detail alamat.' }}</p>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 32px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Deskripsi Usulan / Permasalahan</h3>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                <p style="font-size: 14px; color: #334155; margin: 0; line-height: 1.6;">{!! nl2br(e($usulan->deskripsi_usulan)) !!}</p>
            </div>
        </div>

        @if($usulan->latitude && $usulan->longitude)
        <div style="margin-bottom: 32px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Peta Lokasi</h3>
            <div id="map" style="height: 350px; width: 100%; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"></div>
        </div>
        @endif

        @if($usulan->photo)
            @php $fotos = json_decode($usulan->photo, true); @endphp
            @if(is_array($fotos) && count($fotos) > 0)
            <div style="margin-bottom: 32px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Galeri Foto Pendukung</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                    @foreach($fotos as $f)
                        <div style="width: 200px; height: 150px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <a href="{{ Storage::url($f) }}" target="_blank">
                                <img src="{{ Storage::url($f) }}" alt="Foto Usulan" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
</div>

@if($usulan->latitude && $usulan->longitude)
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lat = {{ $usulan->latitude }};
        let lng = {{ $usulan->longitude }};
        
        let map = L.map('map').setView([lat, lng], 17);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>{{ $usulan->nama_pengusul }}</b><br>Usulan Masyarakat")
            .openPopup();
    });
</script>
@endif
@endsection
