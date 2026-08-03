<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #1F6F5F; color: #ffffff; height: 40px; vertical-align: middle;">
                DATA INVENTARISASI SURVEI RESES
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center; font-size: 12px; font-style: italic; background-color: #1F6F5F; color: #ffffff; height: 30px; vertical-align: middle;">
                Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">No</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Tanggal Reses</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Nama Dewan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kecamatan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Kelurahan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Alamat</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Permintaan/Keluhan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Keterangan</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Volume (P x L x T)</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Estimasi Biaya</th>
            <th style="text-align: center; font-weight: bold; background-color: #76b38e; color: #ffffff; border: 1px solid #000000;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
            <tr>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">{{ \Carbon\Carbon::parse($item->tanggal_reses)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->dewan ? $item->dewan->nama : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kecamatan ? $item->kecamatan->nama_kecamatan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->alamat }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">
                    @if($item->permintaan) Permintaan: {{ $item->permintaan }} @endif
                    @if($item->permintaan && $item->keluhan) <br> @endif
                    @if($item->keluhan) Keluhan: {{ $item->keluhan }} @endif
                </td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $item->keterangan ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top;">
                    V: {{ $item->volume ?? 0 }} m3
                    (P:{{ $item->panjang ?? 0 }} L:{{ $item->lebar ?? 0 }} T:{{ $item->tinggi ?? 0 }})
                </td>
                <td style="text-align: right; border: 1px solid #000000; vertical-align: top;">Rp {{ number_format($item->estimasi_biaya ?? 0, 0, ',', '.') }}</td>
                <td style="text-align: center; border: 1px solid #000000; vertical-align: top; font-weight: bold; color: {{ $item->status == 'Selesai' ? '#059669' : ($item->status == 'Diproses' ? '#1d4ed8' : '#d97706') }}">
                    {{ $item->status }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
