@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h2 class="content-card-title">Detail Inventarisasi Reses</h2>
        <a href="{{ route('admin.survei-reses.index') }}" class="btn btn-ghost">Kembali ke Daftar</a>
    </div>

    <div style="padding: 24px; font-size: 14px; color: #374151;">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Informasi Wilayah & Pelaksana</h3>
                
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; width: 150px;">Nama Dewan</td>
                        <td style="padding: 8px 0;">: {{ $survei->dewan ? $survei->dewan->nama : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Tanggal Reses</td>
                        <td style="padding: 8px 0;">: {{ \Carbon\Carbon::parse($survei->tanggal_reses)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Kecamatan</td>
                        <td style="padding: 8px 0;">: {{ $survei->kecamatan ? $survei->kecamatan->nama_kecamatan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Kelurahan</td>
                        <td style="padding: 8px 0;">: {{ $survei->kelurahan ? $survei->kelurahan->nama_kelurahan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Alamat Lokasi</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $survei->alamat }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Status Progress</td>
                        <td style="padding: 8px 0;">: 
                            @if($survei->status == 'Baru')
                                <span style="background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Baru</span>
                            @elseif($survei->status == 'Disurvei')
                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Disurvei</span>
                            @elseif($survei->status == 'Diproses')
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Diproses</span>
                            @else
                                <span style="background: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 500;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Aspirasi & Spesifikasi Fisik</h3>
                
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top; width: 150px;">Aspirasi Masyarakat</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $survei->permintaan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Keterangan Tambahan</td>
                        <td style="padding: 8px 0; vertical-align: top;">: {{ $survei->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Dimensi (PxLxT)</td>
                        <td style="padding: 8px 0;">: {{ $survei->panjang ?? 0 }}m x {{ $survei->lebar ?? 0 }}m x {{ $survei->tinggi ?? 0 }}m</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Volume Total</td>
                        <td style="padding: 8px 0;">: {{ $survei->volume ?? 0 }} m&sup3;</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Estimasi Biaya</td>
                        <td style="padding: 8px 0; font-weight: 700; color: #059669;">: Rp {{ number_format($survei->estimasi_biaya ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">Foto Kondisi Lapangan</h3>
            
            @if($survei->foto)
                @php
                    $fotos = json_decode($survei->foto, true) ?? [];
                @endphp
                @if(is_array($fotos) && count($fotos) > 0)
                    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                        @foreach($fotos as $fotoPath)
                            <div style="width: 200px; height: 150px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                                <img src="{{ asset('storage/'.$fotoPath) }}" alt="Foto Reses" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 24px; background: #f9fafb; text-align: center; border-radius: 8px; color: #6b7280; border: 1px dashed #d1d5db;">
                        Format foto tidak valid atau kosong.
                    </div>
                @endif
            @else
                <div style="padding: 24px; background: #f9fafb; text-align: center; border-radius: 8px; color: #6b7280; border: 1px dashed #d1d5db;">
                    Belum ada foto yang diunggah.
                </div>
            @endif
        </div>

        <div style="margin-top: 40px; text-align: right;">
            <a href="{{ route('admin.survei-reses.edit', $survei->id) }}" class="btn btn-primary" style="padding: 10px 20px;">Edit Data Ini</a>
        </div>
    </div>
</div>
@endsection
