@extends('layouts.admin')

@section('title', 'Peta Lokasi Pekerjaan SDA')
@section('page_title', 'Peta Lokasi Pekerjaan SDA')

@push('head')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-container {
        height: calc(100vh - 180px);
        min-height: 500px;
        width: 100%;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        z-index: 1;
    }
    
    .popup-content {
        font-family: 'Inter', sans-serif;
        min-width: 220px;
    }
    .popup-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 6px;
    }
    .popup-info {
        font-size: 12px;
        color: #4b5563;
        margin-bottom: 4px;
    }
    .popup-progress-container {
        width: 100%;
        background-color: #e5e7eb;
        border-radius: 999px;
        height: 6px;
        margin-top: 8px;
        margin-bottom: 12px;
        overflow: hidden;
    }
    .popup-progress-bar {
        height: 100%;
        background-color: #059669;
        border-radius: 999px;
    }
    .popup-btn {
        display: block;
        width: 100%;
        text-align: center;
        background-color: #1F6F5F;
        color: white !important;
        padding: 6px 0;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: background-color 0.2s;
    }
    .popup-btn:hover {
        background-color: #166534;
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 18px; font-weight: 700; color: #1F6F5F; margin: 0;">Peta Sebaran Pekerjaan SDA</h2>
        <p style="font-size: 13px; color: #6b7280; margin: 4px 0 0;">Menampilkan {{ $pekerjaan->count() }} titik pekerjaan yang memiliki data koordinat valid.</p>
    </div>
</div>

<div id="map-container"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi Peta (Default ke Jakarta Utara jika tidak ada data)
    let mapCenter = [-6.1214, 106.8927]; // Koordinat tengah Jakut
    let zoomLevel = 12;

    const map = L.map('map-container').setView(mapCenter, zoomLevel);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ambil data pekerjaan dari PHP ke JS
    const pekerjaanData = @json($pekerjaan);
    
    // Group marker untuk auto-zoom bounds
    const markers = [];

    pekerjaanData.forEach(item => {
        let lat = parseFloat(item.latitude);
        let lng = parseFloat(item.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            // Setup Progress Color (Hijau jika 100%, Biru jika sedang berjalan, Kuning jika 0%)
            let progressColor = '#059669';
            if(item.progress == 0) progressColor = '#d97706';
            else if(item.progress < 100) progressColor = '#2563eb';

            // Limit deskripsi untuk popup
            let deskripsi = item.deskripsi ? item.deskripsi : 'Tidak ada nama pekerjaan';
            if (deskripsi.length > 50) deskripsi = deskripsi.substring(0, 50) + '...';

            let alamat = item.alamat ? item.alamat : 'Alamat tidak diketahui';
            if (alamat.length > 40) alamat = alamat.substring(0, 40) + '...';

            // Buat HTML Content untuk Popup
            let popupHtml = `
                <div class="popup-content">
                    <div class="popup-title">${deskripsi}</div>
                    <div class="popup-info"><strong>Lokasi:</strong> ${alamat}</div>
                    <div class="popup-info"><strong>Progress:</strong> ${item.progress || 0}%</div>
                    
                    <div class="popup-progress-container">
                        <div class="popup-progress-bar" style="width: ${item.progress || 0}%; background-color: ${progressColor}"></div>
                    </div>

                    <a href="/admin/pekerjaan-sda/${item.id}" class="popup-btn">Lihat Detail Pekerjaan</a>
                </div>
            `;

            let marker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(popupHtml);
            
            markers.push([lat, lng]);
        }
    });

    // Sesuaikan zoom peta agar semua marker terlihat (jika ada)
    if (markers.length > 0) {
        map.fitBounds(markers, { padding: [30, 30] });
    }
</script>
@endsection
