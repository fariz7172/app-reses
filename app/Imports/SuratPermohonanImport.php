<?php

namespace App\Imports;

use App\Models\SuratPermohonan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SuratPermohonanImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // Cache kecamatan and kelurahan names to avoid excessive queries
        $kecamatans = Kecamatan::all()->keyBy(function ($item) {
            return strtolower(trim($item->nama_kecamatan));
        });
        
        $kelurahans = Kelurahan::all()->keyBy(function ($item) {
            return strtolower(trim($item->nama_kelurahan));
        });

        foreach ($rows as $row) {
            // Check if nomor_surat is empty, if so, skip (or handle appropriately)
            if (empty($row['nomor_surat'])) {
                continue;
            }

            $id_kecamatan = null;
            $id_kelurahan = null;

            if (!empty($row['kecamatan'])) {
                $kec = strtolower(trim($row['kecamatan']));
                if (isset($kecamatans[$kec])) {
                    $id_kecamatan = $kecamatans[$kec]->id;
                }
            }

            if (!empty($row['kelurahan'])) {
                $kel = strtolower(trim($row['kelurahan']));
                if (isset($kelurahans[$kel])) {
                    $id_kelurahan = $kelurahans[$kel]->id;
                }
            }
            
            // Format date if it's an excel date
            $tanggal = null;
            if (!empty($row['tanggal'])) {
                if (is_numeric($row['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
                } else {
                    // Try to parse string date
                    try {
                        $tanggal = \Carbon\Carbon::parse($row['tanggal'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $tanggal = null;
                    }
                }
            }

            SuratPermohonan::updateOrCreate(
                [
                    'nomor_surat' => $row['nomor_surat'],
                ],
                [
                    'tanggal' => $tanggal,
                    'dari' => $row['dari_camat_lurah_ketua_rtrw'] ?? ($row['dari'] ?? null),
                    'id_kecamatan' => $id_kecamatan,
                    'id_kelurahan' => $id_kelurahan,
                    'lokasi' => $row['lokasi'] ?? null,
                    'detail_pemohon' => $row['pengerukanpengurasanperbaikan_saluranpermintaan_u_ditchlainnya'] ?? ($row['deskripsi'] ?? null),
                    'deskripsi' => $row['detail_permohonan'] ?? null,
                    'hasil_survei' => $row['hasil_survei'] ?? null,
                    'photo' => $row['foto'] ?? null,
                    'status' => $row['status'] ?? 'Survey',
                    'catatan' => $row['catatan'] ?? null,
                ]
            );
        }
    }
}
