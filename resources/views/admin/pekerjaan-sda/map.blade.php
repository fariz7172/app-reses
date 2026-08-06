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
<div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div>
        <h2 style="font-size: 18px; font-weight: 700; color: #1F6F5F; margin: 0;">Peta Sebaran Pekerjaan SDA</h2>
        <p style="font-size: 13px; color: #6b7280; margin: 4px 0 0;">Menampilkan <span id="marker-count">{{ $pekerjaan->count() }}</span> titik pekerjaan yang memiliki data koordinat valid.</p>
    </div>
    
    <div>
        <select id="filter-kecamatan" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; cursor: pointer; color: #374151;">
            <option value="">-- Semua Kecamatan --</option>
            @foreach($kecamatans as $kec)
                <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
            @endforeach
        </select>
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
    
    // Layer group untuk marker
    const markerGroup = L.layerGroup().addTo(map);
    
    // Array untuk menampung referensi semua marker
    const allMarkers = [];

    // Definisikan Custom Icon untuk Progress 100% (Warna Hijau)
    const greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Definisikan Custom Icon untuk Hasil Reses (Warna Orange)
    const orangeIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    function renderMarkers() {
        markerGroup.clearLayers();
        allMarkers.length = 0;
        
        let selectedKecamatan = document.getElementById('filter-kecamatan').value;
        let bounds = [];
        let count = 0;

        pekerjaanData.forEach(item => {
            // Filter kecamatan
            if (selectedKecamatan && item.id_kecamatan != selectedKecamatan) {
                return;
            }

            let lat = parseFloat(item.latitude);
            let lng = parseFloat(item.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                // Setup Progress Color (Hijau jika 100%, Biru jika sedang berjalan, Kuning jika 0%)
                let progressColor = '#059669';
                if(item.progress == 0) progressColor = '#d97706';
                else if(item.progress < 100) progressColor = '#2563eb';

                // Limit deskripsi untuk popup
                let deskripsi = item.deskripsi ? item.deskripsi : 'Tidak ada nama pekerjaan';
                let no_surat = item.no_skpd ? item.no_skpd : '-';
                if (deskripsi.length > 50) deskripsi = deskripsi.substring(0, 50) + '...';

                let alamat = item.alamat ? item.alamat : 'Alamat tidak diketahui';
                if (alamat.length > 40) alamat = alamat.substring(0, 40) + '...';

                let dynamicInfoHtml = '';
                if (item.sumber_data === 'Reses') {
                    dynamicInfoHtml = `<div class="popup-info"><strong>Kode Tracking:</strong> ${item.kode_tracking || '-'}</div>`;
                } else {
                    dynamicInfoHtml = `<div class="popup-info"><strong>No. Surat:</strong> ${no_surat}</div>`;
                }

                // Buat HTML Content untuk Popup
                let popupHtml = `
                    <div class="popup-content">
                        <div class="popup-title">${deskripsi}</div>
                        ${dynamicInfoHtml}
                        <div class="popup-info"><strong>Lokasi:</strong> ${alamat}</div>
                        <div class="popup-info"><strong>Progress:</strong> ${item.progress || 0}%</div>
                        
                        <div class="popup-progress-container">
                            <div class="popup-progress-bar" style="width: ${item.progress || 0}%; background-color: ${progressColor}"></div>
                        </div>

                        <a href="/admin/pekerjaan-sda/${item.id}" class="popup-btn">Lihat Detail Pekerjaan</a>
                    </div>
                `;

                let markerOptions = {};
                if (item.sumber_data === 'Reses') {
                    markerOptions.icon = orangeIcon;
                } else if (item.progress == 100) {
                    markerOptions.icon = greenIcon;
                }

                let marker = L.marker([lat, lng], markerOptions).bindPopup(popupHtml);
                
                markerGroup.addLayer(marker);
                bounds.push([lat, lng]);
                count++;
                
                // Simpan referensi ke array untuk search
                allMarkers.push({
                    marker: marker,
                    no_skpd: item.no_skpd ? item.no_skpd.toLowerCase() : '',
                    deskripsi: item.deskripsi ? item.deskripsi.toLowerCase() : '',
                    kode_tracking: item.kode_tracking ? item.kode_tracking.toLowerCase() : '',
                    lat: lat,
                    lng: lng
                });
            }
        });

        document.getElementById('marker-count').innerText = count;

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    }

    // Inisialisasi awal
    renderMarkers();

    // Event listener untuk Filter Kecamatan
    document.getElementById('filter-kecamatan').addEventListener('change', renderMarkers);

    // Event listener untuk Global Search dari layout admin
    const globalSearchInput = document.getElementById('global-search');
    if (globalSearchInput) {
        globalSearchInput.addEventListener('input', function(e) {
            let keyword = e.target.value.toLowerCase().trim();
            if (!keyword) return;

            // Cari marker pertama yang cocok dengan nomor_surat (no_skpd), deskripsi, atau kode_tracking
            let found = allMarkers.find(m => m.no_skpd.includes(keyword) || m.deskripsi.includes(keyword) || m.kode_tracking.includes(keyword));
            
            if (found) {
                // Zoom ke marker dan buka popupnya
                map.setView([found.lat, found.lng], 16, { animate: true });
                found.marker.openPopup();
            }
        });
    }

</script>
@endsection
