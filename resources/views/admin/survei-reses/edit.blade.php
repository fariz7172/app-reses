@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Edit Inventarisasi Reses</h2>
        <a href="{{ route('admin.survei-reses.index') }}" class="btn btn-ghost">Batal & Kembali</a>
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

        <form action="{{ route('admin.survei-reses.update', $survei->eid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nama Dewan</label>
                    <select name="id_dewan" id="select_dewan" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                        <option value="">-- Pilih Dewan --</option>
                        @foreach($dewans as $d)
                            <option value="{{ $d->id }}" data-kec="{{ $d->id_kecamatan }}" {{ old('id_dewan', $survei->id_dewan) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tanggal Reses</label>
                    <input type="date" name="tanggal_reses" value="{{ old('tanggal_reses', $survei->tanggal_reses) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kecamatan</label>
                    <select name="id_kecamatan" id="select_kecamatan" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('id_kecamatan', $survei->id_kecamatan) == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Kelurahan</label>
                    <select name="id_kelurahan" id="select_kelurahan" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">
                        <option value="">-- Pilih Kelurahan --</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->id }}" data-kec="{{ $kel->id_kecamatan }}" {{ old('id_kelurahan', $survei->id_kelurahan) == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Lokasi / Alamat Spesifik</label>
                <textarea name="alamat" rows="2" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">{{ old('alamat', $survei->alamat) }}</textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Permintaan / Aspirasi Masyarakat</label>
                <textarea name="permintaan" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;">{{ old('permintaan', $survei->permintaan) }}</textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f9fafb;" placeholder="Contoh: Lokasi rawan banjir saat pasang...">{{ old('keterangan', $survei->keterangan) }}</textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 24px 0;">
            <h3 style="font-size: 14px; font-weight: 700; color: #1F6F5F; margin-bottom: 16px;">Spesifikasi & Rencana Biaya</h3>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">Panjang (Meter)</label>
                    <input type="number" step="0.01" name="panjang" id="input_panjang" value="{{ old('panjang', $survei->panjang) }}" oninput="hitungVolume()" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">Lebar (Meter)</label>
                    <input type="number" step="0.01" name="lebar" id="input_lebar" value="{{ old('lebar', $survei->lebar) }}" oninput="hitungVolume()" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">Tinggi (Meter)</label>
                    <input type="number" step="0.01" name="tinggi" id="input_tinggi" value="{{ old('tinggi', $survei->tinggi) }}" oninput="hitungVolume()" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">Volume (Otomatis)</label>
                    <input type="number" step="0.01" name="volume" id="input_volume" value="{{ old('volume', $survei->volume) }}" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #f3f4f6;">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Status Progress</label>
                <select name="status" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;">
                    <option value="Baru" {{ old('status', $survei->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Disurvei" {{ old('status', $survei->status) == 'Disurvei' ? 'selected' : '' }}>Disurvei</option>
                    <option value="Diproses" {{ old('status', $survei->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ old('status', $survei->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            @if($survei->foto)
                @php $fotos = json_decode($survei->foto, true); @endphp
                @if(is_array($fotos) && count($fotos) > 0)
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px;">Foto Saat Ini</label>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            @foreach($fotos as $index => $f)
                                <div style="position: relative; width: 120px; height: 120px; border-radius: 8px; overflow: hidden; border: 1px solid #d1d5db; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    <img src="{{ Storage::url($f) }}" alt="Foto Reses" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div style="position: absolute; top: 6px; right: 6px; background: rgba(255,255,255,0.95); border-radius: 6px; padding: 4px 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                        <label style="font-size: 11px; font-weight: 700; color: #dc2626; cursor: pointer; display: flex; align-items: center; gap: 4px; margin: 0;">
                                            <input type="checkbox" name="hapus_foto[]" value="{{ $index }}" style="width: 14px; height: 14px; cursor: pointer;">
                                            Hapus
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small style="color: #6b7280; margin-top: 8px; display: block;">*Centang tombol hapus pada foto yang ingin dibuang.</small>
                    </div>
                @endif
            @endif

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tambahkan Foto Baru (Bisa lebih dari 1)</label>
                <input type="file" name="foto[]" accept="image/*" multiple style="width: 100%; padding: 8px; border: 1px dashed #9ca3af; border-radius: 8px; background: #fafafa;">
                <small style="color: #6b7280; margin-top: 4px; display: block;">*Maksimal 2MB per foto. Upload foto baru akan ditambahkan ke foto sebelumnya (jika ada).</small>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">Update Inventarisasi Reses</button>
            </div>
        </form>
    </div>
</div>

<script>
    function hitungVolume() {
        let p = parseFloat(document.getElementById('input_panjang').value) || 0;
        let l = parseFloat(document.getElementById('input_lebar').value) || 0;
        let t = parseFloat(document.getElementById('input_tinggi').value) || 0;
        
        let vol = p * l * t;
        if(vol > 0) {
            document.getElementById('input_volume').value = vol.toFixed(2);
        } else {
            document.getElementById('input_volume').value = '';
        }
    }

    document.getElementById('select_dewan').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let kecId = selectedOption.getAttribute('data-kec');
        
        if (kecId) {
            let selectKec = document.getElementById('select_kecamatan');
            selectKec.value = kecId;
            let event = new Event('change');
            selectKec.dispatchEvent(event);
        }
    });

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
</script>
@endsection
