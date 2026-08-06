<?php

namespace App\Imports;

use App\Models\SurveiReses;
use App\Models\PekerjaanSda;
use App\Models\Dewan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ResesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Cache lookups to avoid N+1 queries
        $dewans = Dewan::all()->keyBy(function ($item) {
            return strtolower(trim($item->nama));
        });

        $kecamatans = Kecamatan::all()->keyBy(function ($item) {
            return strtolower(trim($item->nama_kecamatan));
        });
        
        $kelurahans = Kelurahan::all()->keyBy(function ($item) {
            return strtolower(trim($item->nama_kelurahan));
        });

        foreach ($rows as $row) {
            // Check if essential fields are empty, if so, skip
            if (empty($row['kode_tracking_referensi']) && empty($row['skpdukpd'])) {
                continue;
            }

            // Extract IDs from relationships
            $id_dewan = null;
            $id_kecamatan = null;
            $id_kelurahan = null;

            if (!empty($row['nama_dewan'])) {
                $dwn = trim($row['nama_dewan']);
                $dwnLower = strtolower($dwn);
                if (isset($dewans[$dwnLower])) {
                    $id_dewan = $dewans[$dwnLower]->id;
                } else {
                    $newDewan = Dewan::create(['nama' => $dwn]);
                    $dewans[$dwnLower] = $newDewan;
                    $id_dewan = $newDewan->id;
                }
            } else {
                // If excel row doesn't have Nama Dewan, assign to a default "Tidak Diketahui"
                if (!isset($dewans['tidak diketahui'])) {
                    $defaultDewan = Dewan::create(['nama' => 'Tidak Diketahui']);
                    $dewans['tidak diketahui'] = $defaultDewan;
                }
                $id_dewan = $dewans['tidak diketahui']->id;
            }

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

            // Parse Dates
            $tgl_input = $this->parseDate($row['tanggal_input_data'] ?? null);
            $tgl_survei = $this->parseDate($row['tanggal_survei'] ?? null);

            $kode_tracking = $row['kode_tracking_referensi'] ?? null;
            $no_skpd = $row['skpdukpd'] ?? null;
            $no_reses_excel = $row['no'] ?? null;

            // Skip if SurveiReses already exists with this no_reses
            if ($no_reses_excel) {
                $exists = SurveiReses::where('no_reses', $no_reses_excel)->exists();
                if ($exists) {
                    continue;
                }
            }

            // Skip if PekerjaanSda already exists with this kode_tracking
            if ($kode_tracking) {
                $exists = PekerjaanSda::where('kode_tracking', $kode_tracking)->exists();
                if ($exists) {
                    continue;
                }
            } elseif ($no_skpd) {
                // fallback check using no_skpd if tracking code is empty
                $exists = PekerjaanSda::where('no_skpd', $no_skpd)->exists();
                if ($exists) {
                    continue;
                }
            }

            $statusTindakLanjut = trim($row['status_tindaklanjut'] ?? '');
            $statusSurvei = (strtolower($statusTindakLanjut) === 'selesai') ? 'Selesai' : 'Diproses';

            // 1. Create SurveiReses
            $survei = SurveiReses::create([
                'no_reses' => $row['no'] ?? null,
                'id_dewan' => $id_dewan,
                'id_kecamatan' => $id_kecamatan,
                'id_kelurahan' => $id_kelurahan,
                'tanggal_reses' => $tgl_input, // Using tanggal input data as tanggal reses
                'alamat' => $row['alamat'] ?? null,
                'keluhan' => $row['isi_pesan_deskripsi_usulan'] ?? null,
                'volume' => $row['volume_panjang'] ?? null,
                'status' => $statusSurvei,
            ]);

            // 2. Create PekerjaanSda
            PekerjaanSda::create([
                'id_survei_reses' => $survei->id,
                'no_skpd' => $no_skpd,
                'tahun_monev' => $row['tahun_monev_tahun_ulm'] ?? null,
                'sumber_data' => 'Reses',
                'tgl_input' => $tgl_input,
                'rincian_sumber_data' => $row['rincian_sumber_data'] ?? null,
                'kode_tracking' => $kode_tracking,
                'id_dewan' => $id_dewan,
                'id_kecamatan' => $id_kecamatan,
                'id_kelurahan' => $id_kelurahan,
                'alamat' => $row['alamat'] ?? null,
                'deskripsi' => $row['isi_pesan_deskripsi_usulan'] ?? null,
                'lingkup_kewenangan' => $row['lingkup_kewenangan'] ?? null,
                'kategori_pekerjaan' => $row['kategori_pekerjaan'] ?? null,
                'volume_panjang' => $row['volume_panjang'] ?? null,
                'kategori_prioritas' => $row['kategori_prioritas'] ?? null,
                'status_tindak_lanjut' => $row['status_tindaklanjut'] ?? null,
                'tgl_survei' => $tgl_survei,
                'tahun_dikerjakan' => $row['dikerjakan_tahun'] ?? null,
                'metode_pekerjaan' => $row['metode_pekerjaan'] ?? null,
                'progress' => 0,
            ]);
        }
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        if (is_numeric($dateValue)) {
            return Date::excelToDateTimeObject($dateValue)->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
