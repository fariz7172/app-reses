@extends('layouts.admin')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Tambah Pekerjaan SDA</h2>
        <a href="{{ route('admin.pekerjaan-sda.index') }}" class="btn btn-ghost">Kembali</a>
    </div>

    <div style="padding: 24px;">
        <!-- TAHAP 1: TARIK DATA (OPSIONAL) -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #166534; margin-bottom: 12px;">Integrasi Data Cepat</h3>
            <p style="font-size: 13px; color: #15803d; margin-bottom: 16px;">Jika pekerjaan ini bersumber dari usulan Reses Dewan, Anda bisa menarik datanya secara otomatis untuk menghemat waktu pengetikan.</p>
            
            <form action="{{ route('admin.pekerjaan-sda.create') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex-grow: 1; max-width: 500px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #166534; margin-bottom: 6px;">Pilih Data Survei Reses (Status: Baru/Disurvei)</label>
                    <select name="survei_id" style="width: 100%; padding: 10px; border: 1px solid #86efac; border-radius: 8px; font-family: inherit; font-size: 14px;">
                        <option value="">-- Pilih Laporan Reses --</option>
                        @foreach($survei_reses_list as $reses)
                            <option value="{{ $reses->id }}" {{ request('survei_id') == $reses->id ? 'selected' : '' }}>
                                Reses #{{ $reses->id }} | Dewan: {{ $reses->dewan ? $reses->dewan->nama : '-' }} | Lokasi: {{ Str::limit($reses->alamat, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="background: #166534; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Tarik Data</button>
            </form>
        </div>

        @if ($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TAHAP 2: FORM UTAMA -->
        <form action="{{ route('admin.pekerjaan-sda.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if($survei_terpilih)
                <!-- Hidden input untuk menautkan id_survei_reses -->
                <input type="hidden" name="id_survei_reses" value="{{ $survei_terpilih->id }}">
            @endif

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">A. Informasi Sumber & Tracking</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Sumber Data *</label>
                    <select name="sumber_data" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="Reses" {{ old('sumber_data', $survei_terpilih ? 'Reses' : '') == 'Reses' ? 'selected' : '' }}>Reses</option>
                        <option value="Musrenbang" {{ old('sumber_data') == 'Musrenbang' ? 'selected' : '' }}>Musrenbang</option>
                        <option value="Masyarakat" {{ old('sumber_data') == 'Masyarakat' ? 'selected' : '' }}>Masyarakat</option>
                        <option value="Lainnya" {{ old('sumber_data') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Dewan (Jika dari Reses)</label>
                    <select name="id_dewan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Dewan --</option>
                        @foreach($dewans as $d)
                            <option value="{{ $d->id }}" {{ old('id_dewan', $survei_terpilih ? $survei_terpilih->id_dewan : '') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kode Tracking</label>
                    <input type="text" name="kode_tracking" value="{{ old('kode_tracking') }}" placeholder="Trk-..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">No SKPD/UKPD</label>
                    <input type="text" name="no_skpd" value="{{ old('no_skpd') }}" placeholder="001/SKPD/..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tahun Monev</label>
                    <input type="number" name="tahun_monev" value="{{ old('tahun_monev', date('Y')) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tanggal Input</label>
                    <input type="date" name="tgl_input" value="{{ old('tgl_input', date('Y-m-d')) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">B. Lokasi & Pekerjaan</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kecamatan</label>
                    <select name="id_kecamatan" id="select_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('id_kecamatan', $survei_terpilih ? $survei_terpilih->id_kecamatan : '') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kelurahan</label>
                    <select name="id_kelurahan" id="select_kelurahan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kelurahan --</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->id }}" data-kec="{{ $kel->id_kecamatan }}" {{ old('id_kelurahan', $survei_terpilih ? $survei_terpilih->id_kelurahan : '') == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('alamat', $survei_terpilih ? $survei_terpilih->alamat : '') }}</textarea>
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
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Deskripsi / Nama Pekerjaan</label>
                <textarea name="deskripsi" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('deskripsi', $survei_terpilih ? $survei_terpilih->permintaan : '') }}</textarea>
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">C. Teknis Pekerjaan</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kategori Pekerjaan</label>
                    <input type="text" name="kategori_pekerjaan" value="{{ old('kategori_pekerjaan') }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Lingkup Kewenangan</label>
                    <input type="text" name="lingkup_kewenangan" value="{{ old('lingkup_kewenangan') }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Metode Pekerjaan</label>
                    <input type="text" name="metode_pekerjaan" value="{{ old('metode_pekerjaan') }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Volume / Panjang (m / m&sup3;)</label>
                    <input type="text" name="volume_panjang" value="{{ old('volume_panjang', $survei_terpilih ? $survei_terpilih->volume : '') }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Progress Pekerjaan (%)</label>
                    <input type="number" name="progress" min="0" max="100" value="{{ old('progress', 0) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tahun Dikerjakan</label>
                    <input type="number" name="tahun_dikerjakan" value="{{ old('tahun_dikerjakan', date('Y')) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Upload Foto Pelaksanaan</label>
                <input type="file" name="photo[]" accept="image/*" multiple style="width: 100%; padding: 8px; border: 1px dashed #9ca3af; border-radius: 8px; background: #fafafa;">
                <small style="color: #6b7280; margin-top: 4px; display: block;">*Format gambar (JPG/PNG), max 2MB per foto. Bisa upload lebih dari 1.</small>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Simpan Daftar Pekerjaan</button>
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
                timeout: 10000,
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
            // Fallback: Jika alamat terlalu spesifik (seperti RT/RW) dan OSM gagal mencarinya, cari kelurahan saja
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

    // Bind event
    document.getElementById('select_kelurahan').addEventListener('change', geocodeLocation);
    document.getElementsByName('alamat')[0].addEventListener('blur', geocodeLocation);
</script>

<script>
    // Filter Kelurahan berdasarkan Kecamatan (Hanya script dasar jika diubah)
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

    // Jalankan filter saat awal load jika ada selected kecamatan
    window.onload = function() {
        let kecSelect = document.getElementById('select_kecamatan');
        if(kecSelect.value) {
            let event = new Event('change');
            kecSelect.dispatchEvent(event);
            
            // Set kembali nilai kelurahan yang tadi selected dari server
            let kelIdServer = "{{ $survei_terpilih ? $survei_terpilih->id_kelurahan : '' }}";
            if(kelIdServer) {
                document.getElementById('select_kelurahan').value = kelIdServer;
            }
        }
    };
</script>
@endsection
