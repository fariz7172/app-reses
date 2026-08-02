@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map { height: 350px; border-radius: 8px; z-index: 1; border: 1px solid #d1d5db; }
</style>
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Buat Surat Permohonan / Laporan /Hasil Survei</h2>
        <a href="{{ route('admin.surat-permohonan.index') }}" class="btn btn-ghost">Kembali</a>
    </div>

    <div style="padding: 24px;">
        <!-- TAHAP 1: TARIK DATA PEKERJAAN SDA -->
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #1e40af; margin-bottom: 12px;">Integrasi dengan Daftar Pekerjaan SDA</h3>
            <p style="font-size: 13px; color: #1d4ed8; margin-bottom: 16px;">Anda dapat menarik data Pekerjaan SDA untuk dilaporkan / dimohonkan dalam surat ini.</p>
            
            <form action="{{ route('admin.surat-permohonan.create') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex-grow: 1; max-width: 500px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #1e40af; margin-bottom: 6px;">Pilih Pekerjaan SDA (Tarik Data)</label>
                    <select name="pekerjaan_id" style="width: 100%; padding: 10px; border: 1px solid #93c5fd; border-radius: 8px; font-family: inherit; font-size: 14px;">
                        <option value="">-- Pilih Pekerjaan SDA --</option>
                        @foreach($pekerjaan_list as $pek)
                            <option value="{{ $pek->id }}" {{ request('pekerjaan_id') == $pek->id ? 'selected' : '' }}>
                                Pekerjaan #{{ $pek->id }} | Kategori: {{ $pek->kategori_pekerjaan ?? '-' }} | Ket: {{ Str::limit($pek->deskripsi, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="background: #1e40af; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Tarik Data</button>
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

        <!-- FORM UTAMA -->
        <form action="{{ route('admin.surat-permohonan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if($pekerjaan_terpilih)
                <input type="hidden" name="id_pekerjaan_sda" value="{{ $pekerjaan_terpilih->id }}">
            @endif

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">A. Detail Surat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tanggal Surat *</label>
                    <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nomor Surat *</label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required placeholder="Contoh: 001/SP/2026" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Dari / Pengirim *</label>
                    <input type="text" name="dari" value="{{ old('dari') }}" required placeholder="Contoh: Kepala Suku Dinas SDA Jakut" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Detail Pemohon / Tujuan Utama</label>
                    <input type="text" name="detail_pemohon" value="{{ old('detail_pemohon') }}" placeholder="Contoh: Dinas SDA Provinsi DKI Jakarta" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">B. Informasi Wilayah & Isi Surat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kecamatan</label>
                    <select name="id_kecamatan" id="select_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('id_kecamatan', $pekerjaan_terpilih ? $pekerjaan_terpilih->id_kecamatan : '') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kelurahan</label>
                    <select name="id_kelurahan" id="select_kelurahan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kelurahan --</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->id }}" data-kec="{{ $kel->id_kecamatan }}" {{ old('id_kelurahan', $pekerjaan_terpilih ? $pekerjaan_terpilih->id_kelurahan : '') == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Titik Koordinat (Peta)</label>
                <div id="map" style="margin-bottom: 10px;"></div>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $pekerjaan_terpilih ? $pekerjaan_terpilih->latitude : '') }}" placeholder="Latitude" style="flex:1; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" readonly>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $pekerjaan_terpilih ? $pekerjaan_terpilih->longitude : '') }}" placeholder="Longitude" style="flex:1; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" readonly>
                </div>
                <small style="color: #6b7280; display: block; margin-top: 5px;">Klik pada peta untuk menentukan titik koordinat.</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Lokasi</label>
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <textarea name="lokasi" id="lokasi" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('lokasi', $pekerjaan_terpilih ? $pekerjaan_terpilih->alamat : '') }}</textarea>
                    <button type="button" onclick="cariLokasiPeta(event)" style="background: #1F6F5F; color: white; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap; height: fit-content;">Cari di Peta</button>
                </div>
                <small style="color: #6b7280; display: block; margin-top: 5px;">Ketik nama jalan (tanpa RT/RW) lalu klik "Cari di Peta" agar titik koordinat otomatis pindah.</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Deskripsi Pekerjaan / Isi Permohonan</label>
                <textarea name="deskripsi" rows="4" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('deskripsi', $pekerjaan_terpilih ? $pekerjaan_terpilih->deskripsi : '') }}</textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Hasil Survei Lapangan (Ringkasan)</label>
                <textarea name="hasil_survei" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('hasil_survei') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Catatan Tambahan</label>
                    <input type="text" name="catatan" value="{{ old('catatan') }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Status Pengajuan</label>
                    <select name="status" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="Menunggu" {{ old('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu (Belum Diproses)</option>
                        <option value="Diajukan" {{ old('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>Diproses (Pekerjaan SDA)</option>
                        <option value="Disetujui" {{ old('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ old('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Upload Foto / Scan Dokumen Surat (Bisa lebih dari 1)</label>
                <input type="file" name="photo[]" accept="image/*" multiple style="width: 100%; padding: 8px; border: 1px dashed #9ca3af; border-radius: 8px; background: #fafafa;">
                <small style="color: #6b7280; margin-top: 4px; display: block;">*Format gambar (JPG/PNG), max 2MB per foto.</small>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Simpan Surat Permohonan</button>
            </div>
        </form>
    </div>
</div>

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
            
            let kelIdServer = "{{ $pekerjaan_terpilih ? $pekerjaan_terpilih->id_kelurahan : '' }}";
            if(kelIdServer) {
                document.getElementById('select_kelurahan').value = kelIdServer;
            }
        }
    };

    // Fix Leaflet default icon path
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    });

    // Leaflet Map Logic
    var initialLat = document.getElementById('latitude').value || -6.121435;
    var initialLng = document.getElementById('longitude').value || 106.774124;
    var map = L.map('map').setView([initialLat, initialLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([initialLat, initialLng], {draggable: true});
    
    if(document.getElementById('latitude').value) {
        marker.addTo(map);
    }

    function updateInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        if(!map.hasLayer(marker)){
            marker.addTo(map);
        }
        marker.setLatLng([lat, lng]);
        updateInputs(lat, lng);
    });

    marker.on('dragend', function(e) {
        var lat = marker.getLatLng().lat;
        var lng = marker.getLatLng().lng;
        updateInputs(lat, lng);
    });

    window.cariLokasiPeta = function(event) {
        let kec = document.getElementById('select_kecamatan');
        let kel = document.getElementById('select_kelurahan');
        let kecText = kec.options[kec.selectedIndex] && kec.value ? kec.options[kec.selectedIndex].text : '';
        let kelText = kel.options[kel.selectedIndex] && kel.value ? kel.options[kel.selectedIndex].text : '';
        let jalan = document.getElementById('lokasi').value;

        // Clean up RT/RW to get better geocoding results
        let cleanJalan = jalan.replace(/rt[\s\.\-]*\d+/gi, '').replace(/rw[\s\.\-]*\d+/gi, '').trim();

        let queryParts = [];
        if(cleanJalan) queryParts.push(cleanJalan);
        if(kelText) queryParts.push(kelText);
        if(kecText) queryParts.push(kecText);
        queryParts.push("Jakarta Utara");

        let query = queryParts.join(", ");
        
        let btn = event.target;
        let oldText = btn.innerText;
        btn.innerText = "Mencari...";
        btn.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                btn.innerText = oldText;
                btn.disabled = false;
                if(data && data.length > 0) {
                    let lat = parseFloat(data[0].lat);
                    let lon = parseFloat(data[0].lon);
                    map.setView([lat, lon], 17);
                    if(!map.hasLayer(marker)) marker.addTo(map);
                    marker.setLatLng([lat, lon]);
                    updateInputs(lat, lon);
                } else {
                    alert('Lokasi tidak ditemukan oleh sistem peta. \n\nCobalah untuk:\n1. Hapus RT/RW atau nomor rumah pada kolom lokasi\n2. Cukup ketik nama jalan utama\n3. Atau geser pin manual pada peta');
                }
            }).catch(e => {
                btn.innerText = oldText;
                btn.disabled = false;
                alert('Gagal menghubungi server pencarian peta.');
            });
    }
</script>
@endsection
