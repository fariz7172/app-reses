<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak BAST - {{ $pekerjaan->no_skpd ?? 'SDA' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #525659; /* Latar belakang abu-abu seperti PDF viewer */
        }
        
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm 20mm 20mm 20mm; /* Dikurangi padding atasnya */
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            box-sizing: border-box;
            position: relative;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 2px;
            text-align: center;
            position: relative;
        }
        .kop-surat::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 1px;
            background-color: #000;
        }
        .logo-dki {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: auto;
        }
        .kop-teks h1, .kop-teks h2, .kop-teks h3 {
            margin: 0;
            padding: 0;
            font-weight: normal;
        }
        .kop-teks h1 { font-size: 14pt; }
        .kop-teks h2 { font-size: 16pt; font-weight: bold; }
        .kop-teks h3 { font-size: 14pt; font-weight: bold; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }
        
        .kode-pos {
            text-align: right;
            font-size: 10pt;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin: 10px 0;
        }
        .judul-surat h4 {
            margin: 0;
            font-size: 11pt;
            text-decoration: underline;
            font-weight: bold;
        }
        .judul-surat p {
            margin: 0;
            font-size: 11pt;
        }

        /* Isi Surat */
        .isi-surat {
            text-align: justify;
        }
        
        .identitas {
            margin-bottom: 10px;
        }
        
        .identitas-table {
            width: 100%;
            border-collapse: collapse;
        }
        .identitas-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        
        .pernyataan {
            margin-top: 10px;
        }
        .pernyataan ol {
            padding-left: 20px;
            margin-top: 5px;
        }
        .pernyataan li {
            margin-bottom: 5px;
        }

        /* Tanda Tangan */
        .tanda-tangan {
            margin-top: 30px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .ttd-box {
            width: 45%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .ttd-space {
            min-height: 60px;
            flex-grow: 1;
        }

        @media print {
            body {
                background: none;
            }
            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                padding: 0; /* Margin handled by browser print settings */
            }
            @page {
                size: A4;
                margin: 10mm 20mm 20mm 20mm; /* Dikurangi margin atasnya */
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="page">
        <!-- Kop Surat -->
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="{{ asset('assets/kop.png') }}" alt="Kop Surat" style="width: 100%; height: auto;">
        </div>

        <!-- Judul -->
        <div class="judul-surat">
            <h4>BERITA ACARA SERAH TERIMA PEKERJAAN</h4>
            <p>Nomor: {{ $nomorSurat }}</p>
        </div>

        <!-- Isi -->
        <div class="isi-surat">
            <p>Pada hari ini, {{ $namaHari }} tanggal {{ $terbilangTgl }} Bulan {{ $blnStr }} Tahun {{ $terbilangThn }} ({{ $tglStr }} {{ $blnStr }} {{ $thnStr }}), bertempat di Jakarta, yang bertanda tangan di bawah ini :</p>
            
            <div class="identitas">
                <table class="identitas-table">
                    <tr>
                        <td style="width: 20px;">1.</td>
                        <td style="width: 80px;">Nama</td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $pekerjaan->pelaksana->nama ?? '..........................' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>NIP</td>
                        <td>:</td>
                        <td>{{ $pekerjaan->pelaksana->nip ?? '..........................' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>{{ $pekerjaan->pelaksana->jabatan ?? '..........................' }}<br>Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara</td>
                    </tr>
                </table>
                <p style="margin-top: 5px; margin-bottom: 15px; padding-left: 110px;">Untuk selanjutnya disebut sebagai <strong>PIHAK KESATU</strong></p>
            </div>

            <div class="identitas">
                <table class="identitas-table">
                    <tr>
                        <td style="width: 20px;">2.</td>
                        <td style="width: 80px;">Nama</td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $pekerjaan->vendor->jabatan ?? '..........................' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>Direktur. {{ $pekerjaan->vendor->nama ?? '..........................' }}</td>
                    </tr>
                </table>
                <p style="margin-top: 5px; margin-bottom: 15px; padding-left: 110px;">Untuk selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
            </div>

            <div class="pernyataan">
                Dengan ini menyatakan bahwa:
                <ol>
                    <li><strong>PIHAK KESATU</strong> berpendapat bahwa setelah diadakan pemeriksaan hasil pekerjaan sesuai dengan SPK tersebut di atas telah diselesaikan 100% dengan hasil pekerjaan dalam kondisi baik.</li>
                    <li><strong>PIHAK KEDUA</strong> menyerahkan hasil kegiatan yang telah diselesaikan kepada <strong>PIHAK KESATU</strong>.</li>
                    <li><strong>PIHAK KESATU</strong> menerima hasil pekerjaan yang telah selesai dilaksanakan oleh <strong>PIHAK KEDUA</strong>.</li>
                </ol>
            </div>

            <p style="margin-top: 10px;">Demikian Berita Acara ini di tandatangani di Jakarta, pada hari dan tanggal tersebut di muka oleh <strong>PIHAK KESATU</strong> dan <strong>PIHAK KEDUA</strong>, serta dibuat rangkap 3 (tiga) untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="tanda-tangan">
            <div class="ttd-box">
                <div>
                    <p style="margin:0;">PIHAK KEDUA<br>{{ $pekerjaan->vendor->nama ?? 'Nama Perusahaan' }}</p>
                </div>
                <div class="ttd-space"></div>
                <div>
                    <p style="margin:0;"><u><strong>{{ $pekerjaan->vendor->jabatan ?? 'Nama Direktur' }}</strong></u><br>Direktur</p>
                </div>
            </div>
            <div class="ttd-box">
                <div>
                    <p style="margin:0;">PIHAK KESATU<br>{{ $pekerjaan->pelaksana->jabatan ?? 'Kepala Seksi' }}<br></p>
                </div>
                <div class="ttd-space"></div>
                <div>
                    <p style="margin:0;"><u><strong>{{ $pekerjaan->pelaksana->nama ?? 'Nama Pelaksana' }}</strong></u><br>NIP: {{ $pekerjaan->pelaksana->nip ?? '..........................' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
