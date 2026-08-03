<?php

namespace App\Exports;

use App\Models\PekerjaanSda;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PekerjaanSdaExport implements FromView, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.pekerjaan-sda.export-excel', [
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // No SKPD
            'C' => 15,  // Tgl Input
            'D' => 12,  // Tahun Monev
            'E' => 18,  // Sumber Data
            'F' => 18,  // Kecamatan
            'G' => 18,  // Kelurahan
            'H' => 45,  // Alamat
            'I' => 50,  // Deskripsi
            'J' => 25,  // Kategori
            'K' => 15,  // Progress
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mendapatkan jumlah baris (header 3 baris + data)
        $highestRow = $sheet->getHighestRow();
        
        // 1. Style untuk header tabel besar
        $sheet->getStyle('A1:K2')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // 2. Style border luar dan dalam untuk seluruh data tabel
        $sheet->getStyle('A3:K' . $highestRow)->applyFromArray([
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
