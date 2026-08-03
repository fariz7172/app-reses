<?php

namespace App\Exports;

use App\Models\SuratPermohonan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SuratPermohonanExport implements FromView, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.surat-permohonan.export-excel', [
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 25,  // No Surat
            'D' => 25,  // Pengirim
            'E' => 15,  // Kecamatan
            'F' => 15,  // Kelurahan
            'G' => 35,  // Lokasi
            'H' => 45,  // Perihal
            'I' => 30,  // Hasil Survei
            'J' => 15,  // Status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mendapatkan jumlah baris (header 3 baris + data)
        $highestRow = $sheet->getHighestRow();
        
        // 1. Style untuk header tabel besar
        $sheet->getStyle('A1:J2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // 2. Style border luar dan dalam untuk seluruh data tabel
        $sheet->getStyle('A3:J' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true
            ]
        ]);

        return [];
    }
}
