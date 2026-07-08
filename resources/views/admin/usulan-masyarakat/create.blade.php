@extends('layouts.admin')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Tambah Usulan Masyarakat Baru</h2>
        <a href="{{ route('admin.usulan-masyarakat.index') }}" class="btn btn-ghost">Batal & Kembali</a>
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

        <form action="{{ route('admin.usulan-masyarakat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">A. Informasi Pengusul</h3>
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Pengusul (Individu / Perwakilan) *</label>
                <input type="text" name="nama_pengusul" value="{{ old('nama_pengusul') }}" required placeholder="Contoh: Bpk. Budi / Ketua RT 05..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">B. Lokasi & Usulan</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kecamatan</label>
                    <select name="id_kecamatan" id="select_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('id_kecamatan') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kelurahan</label>
                    <select name="id_kelurahan" id="select_kelurahan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kelurahan --</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->id }}" data-kec="{{ $kel->id_kecamatan }}" {{ old('id_kelurahan') == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Jl. Contoh No. 123, RT/RW...">{{ old('alamat') }}</textarea>
            </div>

            <!-- LEAFLET MAP SECTION -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Titik Kordinat Lokasi (Klik pada peta)</label>
                <div id="map" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #d1d5db; margin-bottom: 10px; z-index: 1;"></div>
                
                <div style="margin-bottom: 12px; text-align: right;">
                    <button type="button" onclick="getCurrentLocation()" style="background: #2563eb; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>
                        Gunakan Lokasi Saya Saat Ini (GPS)
                    </button>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 4px;">Latitude</label>
                        <input type="text" name="latitude" id="lat" value="{{ old('latitude') }}" readonly style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 4px;">Longitude</label>
                        <input type="text" name="longitude" id="lng" value="{{ old('longitude') }}" readonly style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb;">
                    </div>
                </div>
            </div>
            <!-- END LEAFLET MAP SECTION -->
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Deskripsi Usulan / Permasalahan *</label>
                <textarea name="deskripsi_usulan" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Jelaskan detail usulan atau masalah yang terjadi..."></textarea>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Upload Foto Pendukung (Bisa lebih dari 1)</label>
                <input type="file" name="photo[]" accept="image/*" multiple style="width: 100%; padding: 8px; border: 1px dashed #9ca3af; border-radius: 8px; background: #fafafa;">
                <small style="color: #6b7280; margin-top: 4px; display: block;">Format: JPG, PNG. Maks 10MB per foto.</small>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Simpan Usulan</button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    // Leaflet Map Initialization
    let initialLat = document.getElementById('lat').value || -6.1112; // Tanjung Priok default
    let initialLng = document.getElementById('lng').value || 106.8770;
    
    let map = L.map('map').setView([initialLat, initialLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add Geocoder Search Box
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Cari lokasi...'
    })
    .on('markgeocode', function(e) {
        var center = e.geocode.center;
        map.setView(center, 16);
        
        if(marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(center, {draggable: true}).addTo(map);
        updateInputs(center.lat, center.lng);
        setupMarkerEvents();
    })
    .addTo(map);

    let marker = null;
    if(document.getElementById('lat').value && document.getElementById('lng').value) {
        marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
        setupMarkerEvents();
    }

    map.on('click', function(e) {
        if(marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng, {draggable: true}).addTo(map);
        updateInputs(e.latlng.lat, e.latlng.lng);
        setupMarkerEvents();
    });

    function setupMarkerEvents() {
        marker.on('dragend', function(e) {
            let position = marker.getLatLng();
            updateInputs(position.lat, position.lng);
        });
    }

    function updateInputs(lat, lng) {
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('lng').value = lng.toFixed(6);
    }

    // Geolocation API (GPS)
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;
                
                map.setView([lat, lng], 17);
                
                if(marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lng], {draggable: true}).addTo(map);
                updateInputs(lat, lng);
                setupMarkerEvents();
            }, function(error) {
                alert("Gagal mendapatkan lokasi GPS: " + error.message + ". Pastikan Anda memberikan izin akses lokasi pada browser.");
            }, {
                enableHighAccuracy: true,
                timeout: 30000,
                maximumAge: 0
            });
        } else {
            alert("Fitur Geolocation tidak didukung oleh browser Anda.");
        }
    }

    // Auto Geocode from Address/Kelurahan
    function geocodeLocation() {
        let kecSelect = document.getElementById('select_kecamatan');
        let kelSelect = document.getElementById('select_kelurahan');
        let alamat = document.getElementsByName('alamat')[0].value;
        
        let kecText = kecSelect.options[kecSelect.selectedIndex] ? kecSelect.options[kecSelect.selectedIndex].text : '';
        let kelText = kelSelect.options[kelSelect.selectedIndex] ? kelSelect.options[kelSelect.selectedIndex].text : '';
        
        if (kecText === '-- Pilih Kecamatan --') kecText = '';
        if (kelText === '-- Pilih Kelurahan --') kelText = '';
        
        let queryParts = [];
        if(alamat && alamat.trim() !== '') queryParts.push(alamat);
        if(kelText) queryParts.push(kelText);
        if(kecText) queryParts.push(kecText);
        
        if(queryParts.length === 0) return;
        queryParts.push('Jakarta Utara, Indonesia'); // Tambahkan konteks kota
        
        let query = queryParts.join(', ');
        
        performGeocode(query, function(success) {
            if(!success && alamat && alamat.trim() !== '') {
                let fallbackParts = [];
                if(kelText) fallbackParts.push(kelText);
                if(kecText) fallbackParts.push(kecText);
                if(fallbackParts.length > 0) {
                    fallbackParts.push('Jakarta Utara, Indonesia');
                    performGeocode(fallbackParts.join(', '));
                }
            }
        });
    }

    function performGeocode(query, callback = null) {
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if(data && data.length > 0) {
                    let lat = parseFloat(data[0].lat);
                    let lon = parseFloat(data[0].lon);
                    
                    map.setView([lat, lon], 16);
                    
                    if(marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lon], {draggable: true}).addTo(map);
                    updateInputs(lat, lon);
                    setupMarkerEvents();
                    
                    if(callback) callback(true);
                } else {
                    if(callback) callback(false);
                }
            })
            .catch(err => {
                console.error("Geocode error:", err);
                if(callback) callback(false);
            });
    }

    document.getElementById('select_kelurahan').addEventListener('change', geocodeLocation);
    document.getElementsByName('alamat')[0].addEventListener('blur', geocodeLocation);
</script>

<script>
    document.getElementById('select_kecamatan').addEventListener('change', function() {
        let kecId = this.value;
        let kelSelect = document.getElementById('select_kelurahan');
        
        Array.from(kelSelect.options).forEach(opt => {
            if(opt.value === "") return;
            if(opt.getAttribute('data-kec') === kecId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        kelSelect.value = ""; 
    });

    window.onload = function() {
        let kecSelect = document.getElementById('select_kecamatan');
        if(kecSelect.value) {
            let event = new Event('change');
            kecSelect.dispatchEvent(event);
            
            let kelIdOld = "{{ old('id_kelurahan') }}";
            if(kelIdOld) {
                document.getElementById('select_kelurahan').value = kelIdOld;
            }
        }
    };
</script>
@endsection
