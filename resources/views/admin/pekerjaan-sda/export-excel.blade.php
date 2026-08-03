<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #1F6F5F; color: #ffffff; height: 40px; vertical-align: middle;">
                DATA PEKERJAAN SUMBER DAYA AIR (SDA)
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center; font-size: 12px; font-style: italic; background-color: #1F6F5F; color: #ffffff; height: 30px; vertical-align: middle;">
                Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">No</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">No. SKPD/Surat</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Tgl Input</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Tahun Monev</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Sumber Data</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kecamatan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kelurahan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Alamat/Lokasi</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Deskripsi Pekerjaan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kategori Pekerjaan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Progress (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
            <tr>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->no_skpd ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ \Carbon\Carbon::parse($item->tgl_input)->format('d/m/Y') }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ $item->tahun_monev }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->sumber_data }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->alamat }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->deskripsi }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kategori_pekerjaan ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ $item->progress ?? 0 }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
