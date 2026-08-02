<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan - {{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 14px;
        }
        .tanggal {
            text-align: right;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .nomor-surat-tujuan {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .isi-surat {
            line-height: 1.6;
            font-size: 16px;
            text-align: justify;
        }
        .tabel-rincian {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .tabel-rincian th, .tabel-rincian td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .tabel-rincian th {
            background-color: #f3f4f6;
        }
        .tanda-tangan {
            margin-top: 50px;
            width: 300px;
            float: right;
            text-align: center;
            font-size: 16px;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1F6F5F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        @media print {
            body { background: white; }
            .a4-page { box-shadow: none; margin: 0; padding: 15mm; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">Cetak Surat</button>
    
    <a href="{{ route('admin.surat-permohonan.index') }}" style="position: fixed; top: 20px; left: 20px; background: #6b7280; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-family: sans-serif; font-size: 14px;">Kembali</a>

    <div class="a4-page">
        
        <div class="kop-surat">
            <h1>PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
            <h1 style="font-size: 24px;">SUKU DINAS SUMBER DAYA AIR</h1>
            <p>Jalan Yos Sudarso No. 27-29 Tanjung Priok, Jakarta Utara</p>
            <p>Telp. (021) 43936653, Fax. (021) 43936653</p>
        </div>

        <div class="tanggal">
            Jakarta, {{ \Carbon\Carbon::parse($surat->tanggal)->translatedFormat('d F Y') }}
        </div>

        <div class="nomor-surat-tujuan">
            <div>
                <table>
                    <tr><td style="width: 70px;">Nomor</td><td>: {{ $surat->nomor_surat }}</td></tr>
                    <tr><td>Sifat</td><td>: Penting</td></tr>
                    <tr><td>Lampiran</td><td>: 1 (satu) Berkas</td></tr>
                    <tr><td style="vertical-align: top;">Hal</td><td style="vertical-align: top;">: Permohonan Pelaksanaan Pekerjaan SDA</td></tr>
                </table>
            </div>
            <div>
                <p style="margin:0;">Kepada Yth,</p>
                <strong>{{ $surat->detail_pemohon ?: 'Kepala Dinas Sumber Daya Air Prov. DKI Jakarta' }}</strong>
                <p style="margin:0;">di -</p>
                <p style="margin:0; padding-left: 20px;">Jakarta</p>
            </div>
        </div>

        <div class="isi-surat">
            <p>Dengan hormat,</p>
            <p>Menindaklanjuti hasil survei lapangan dan usulan masyarakat, bersama ini kami sampaikan permohonan pelaksanaan Pekerjaan Sumber Daya Air di wilayah Suku Dinas SDA Kota Administrasi Jakarta Utara. Adapun uraian kegiatan yang diusulkan adalah sebagai berikut:</p>
            
            <table class="tabel-rincian">
                <tr>
                    <th style="width: 30%;">Lokasi / Alamat</th>
                    <td>{{ $surat->lokasi ?? '-' }} <br> Kec. {{ $surat->kecamatan ? $surat->kecamatan->nama_kecamatan : '-' }}, Kel. {{ $surat->kelurahan ? $surat->kelurahan->nama_kelurahan : '-' }}</td>
                </tr>
                @if($surat->latitude && $surat->longitude)
                <tr>
                    <th>Titik Koordinat</th>
                    <td>{{ $surat->latitude }}, {{ $surat->longitude }}</td>
                </tr>
                @endif
                <tr>
                    <th>Deskripsi Pekerjaan</th>
                    <td>{{ $surat->deskripsi ?? '-' }}</td>
                </tr>
                @if($surat->pekerjaanSda)
                <tr>
                    <th>Kategori / Kewenangan</th>
                    <td>{{ $surat->pekerjaanSda->kategori_pekerjaan }} / {{ $surat->pekerjaanSda->lingkup_kewenangan }}</td>
                </tr>
                <tr>
                    <th>Volume / Panjang</th>
                    <td>{{ $surat->pekerjaanSda->volume_panjang }}</td>
                </tr>
                <tr>
                    <th>Sumber Data</th>
                    <td>{{ $surat->pekerjaanSda->sumber_data }} (Ref: Trk-{{ $surat->pekerjaanSda->kode_tracking }})</td>
                </tr>
                @endif
                <tr>
                    <th>Hasil Survei</th>
                    <td>{{ $surat->hasil_survei ?? '-' }}</td>
                </tr>
            </table>

            <p>Mengingat pentingnya penanganan pekerjaan tersebut untuk mencegah genangan air dan memperbaiki infrastruktur warga, kami mohon agar usulan ini dapat segera ditindaklanjuti.</p>
            
            <p>Demikian permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
        </div>

        <div class="tanda-tangan">
            <p><strong>Pengirim / Pemohon,</strong></p>
            <br><br><br><br>
            <p style="text-decoration: underline; margin-bottom: 2px;"><strong>{{ strtoupper($surat->dari) }}</strong></p>
            <p style="margin:0;">NIP. ........................................</p>
        </div>

    </div>
</body>
</html>
