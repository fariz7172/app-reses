@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Edit Surat Permohonan / Laporan /Hasil Survei</h2>
        <a href="{{ route('admin.surat-permohonan.index') }}" class="btn btn-ghost">Batal</a>
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

        <form action="{{ route('admin.surat-permohonan.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">A. Detail Surat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tanggal Surat *</label>
                    <input type="date" name="tanggal" required value="{{ old('tanggal', $surat->tanggal) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nomor Surat *</label>
                    <input type="text" name="nomor_surat" required value="{{ old('nomor_surat', $surat->nomor_surat) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Dari / Pengirim *</label>
                    <input type="text" name="dari" required value="{{ old('dari', $surat->dari) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Detail Pemohon / Tujuan Utama</label>
                    <input type="text" name="detail_pemohon" value="{{ old('detail_pemohon', $surat->detail_pemohon) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">B. Informasi Wilayah & Isi Surat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kecamatan</label>
                    <select name="id_kecamatan" id="select_kecamatan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $surat->id_kecamatan == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kelurahan</label>
                    <select name="id_kelurahan" id="select_kelurahan" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">-- Pilih Kelurahan --</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->id }}" data-kec="{{ $kel->id_kecamatan }}" {{ $surat->id_kelurahan == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Lokasi</label>
                <textarea name="lokasi" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('lokasi', $surat->lokasi) }}</textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Deskripsi Pekerjaan / Isi Permohonan</label>
                <textarea name="deskripsi" rows="4" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('deskripsi', $surat->deskripsi) }}</textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Hasil Survei Lapangan (Ringkasan)</label>
                <textarea name="hasil_survei" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('hasil_survei', $surat->hasil_survei) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Catatan Tambahan</label>
                    <input type="text" name="catatan" value="{{ old('catatan', $surat->catatan) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Status Pengajuan</label>
                    <select name="status" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-weight: 600; color: #1d4ed8;">
                        <option value="Diajukan" {{ $surat->status == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="Diterima" {{ $surat->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Diproses" {{ $surat->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Disetujui" {{ $surat->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Selesai" {{ $surat->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ $surat->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Upload Foto Tambahan (Bisa lebih dari 1)</label>
                <input type="file" name="photo[]" accept="image/*" multiple style="width: 100%; padding: 8px; border: 1px dashed #9ca3af; border-radius: 8px; background: #fafafa;">
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Update Surat Permohonan</button>
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
            
            let kelIdServer = "{{ $surat->id_kelurahan }}";
            if(kelIdServer) {
                document.getElementById('select_kelurahan').value = kelIdServer;
            }
        }
    };
</script>
@endsection
