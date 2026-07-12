@extends('layouts.admin')

@section('content')
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
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Lokasi</label>
                <textarea name="lokasi" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('lokasi', $pekerjaan_terpilih ? $pekerjaan_terpilih->alamat : '') }}</textarea>
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
                        <option value="Diajukan" {{ old('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Disetujui" {{ old('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
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
</script>
@endsection
