<table>
    <thead>
        <tr>
            <th colspan="10" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #1F6F5F; color: #ffffff; height: 40px; vertical-align: middle;">
                DATA SURAT PERMOHONAN / USULAN
            </th>
        </tr>
        <tr>
            <th colspan="10" style="text-align: center; font-size: 12px; font-style: italic; background-color: #1F6F5F; color: #ffffff; height: 30px; vertical-align: middle;">
                Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">No</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Tanggal</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Nomor Surat</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Pengirim (Dari)</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kecamatan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kelurahan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Lokasi</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Perihal/Deskripsi</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Hasil Survei</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
            <tr>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->nomor_surat ?? '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->dari }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->lokasi ?? '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->deskripsi ?? '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->hasil_survei ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top; font-weight: bold; color: {{ $item->status == 'Selesai' ? '#059669' : ($item->status == 'Diproses' ? '#1d4ed8' : '#d97706') }}">
                    {{ $item->status }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
