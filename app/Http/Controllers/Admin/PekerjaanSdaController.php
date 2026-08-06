<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PekerjaanSda;
use App\Models\SurveiReses;
use App\Models\SuratPermohonan;
use App\Models\Dewan;
use App\Models\Pelaksana;
use App\Models\Vendor;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class PekerjaanSdaController extends Controller
{
    public function index(Request $request)
    {
        $query = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'suratPermohonan', 'surveiReses']);
        
        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tracking', 'like', "%{$search}%")
                  ->orWhere('no_skpd', 'like', "%{$search}%")
                  ->orWhereHas('surveiReses', function($q2) use ($search) {
                      $q2->where('no_reses', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('sumber_data')) {
            $query->where('sumber_data', $request->sumber_data);
        }

        if ($request->sumber_data === 'Reses') {
            $query->orderBy(
                \App\Models\SurveiReses::selectRaw('CAST(no_reses AS UNSIGNED)')
                    ->whereColumn('survei_reses.id', 'pekerjaan_sdas.id_survei_reses')
                    ->limit(1),
                'desc'
            );
        } else {
            $query->latest();
        }

        $pekerjaan = $query->paginate(10);
        return view('admin.pekerjaan-sda.index', compact('pekerjaan'));
    }

    public function exportExcel(Request $request)
    {
        $query = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'suratPermohonan'])->latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('no_skpd', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        $pekerjaan = $query->get();
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PekerjaanSdaExport($pekerjaan), 
            'Data_Pekerjaan_SDA_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function create(Request $request)
    {
        $survei_id = $request->query('survei_id');
        $survei_terpilih = null;
        
        if ($survei_id) {
            $survei_terpilih = SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->find($survei_id);
        }

        // Ambil data survei reses yang belum di eskalasi
        $kecamatanId = $this->getKecamatanId();

        $surveiQuery = SurveiReses::whereDoesntHave('pekerjaanSda');
        if ($kecamatanId) {
            $surveiQuery->where('id_kecamatan', $kecamatanId);
            $dewans = Dewan::where('id_kecamatan', $kecamatanId)->get();
            $kecamatans = Kecamatan::where('id', $kecamatanId)->get();
        } else {
            $dewans = Dewan::all();
            $kecamatans = Kecamatan::all();
        }
        
        $survei_reses_list = $surveiQuery->get();
        $kelurahans = Kelurahan::all();
        $pelaksanas = Pelaksana::all();
        $vendors = Vendor::all();

        return view('admin.pekerjaan-sda.create', compact('survei_reses_list', 'survei_terpilih', 'dewans', 'kecamatans', 'kelurahans', 'pelaksanas', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_survei_reses' => 'nullable|exists:survei_reses,id',
            'sumber_data' => 'required|string',
            'no_skpd' => 'nullable|string',
            'tahun_monev' => 'nullable|numeric',
            'kode_tracking' => 'nullable|string',
            'id_dewan' => 'nullable|exists:dewans,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'lingkup_kewenangan' => 'nullable|string',
            'kategori_pekerjaan' => 'nullable|string',
            'kategori_prioritas' => 'nullable|string',
            'volume_panjang' => 'nullable|string',
            'metode_pekerjaan' => 'nullable|string',
            'tgl_input' => 'nullable|date',
            'tgl_survei' => 'nullable|date',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'estimasi_tgl_realisasi' => 'nullable|date',
            'tahun_dikerjakan' => 'nullable|numeric',
            'progress' => 'nullable|numeric|min:0|max:100',
            'status_tindak_lanjut' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048',
            'id_pelaksana' => 'nullable|exists:pelaksanas,id',
            'id_vendor' => 'nullable|exists:vendors,id'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('pekerjaan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $pekerjaan = PekerjaanSda::create($validated);

        // Jika bersumber dari Reses, update status reses menjadi Diproses
        if ($pekerjaan->id_survei_reses) {
            $survei = SurveiReses::find($pekerjaan->id_survei_reses);
            if ($survei) {
                $survei->update(['status' => 'Diproses']);
            }
        }

        // Auto-Generate Surat Permohonan
        SuratPermohonan::create([
            'id_pekerjaan_sda' => $pekerjaan->id,
            'tanggal' => date('Y-m-d'),
            'status' => 'Menunggu',
            'id_kecamatan' => $pekerjaan->id_kecamatan,
            'id_kelurahan' => $pekerjaan->id_kelurahan,
            'lokasi' => $pekerjaan->alamat,
            'deskripsi' => $pekerjaan->deskripsi,
        ]);

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Pekerjaan SDA berhasil disimpan dan draf Surat Permohonan otomatis dibuat!');
    }

    public function show(string $id)
    {
        $pekerjaan = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'surveiReses', 'suratPermohonan', 'pelaksana', 'vendor'])->findOrFail($id);
        return view('admin.pekerjaan-sda.show', compact('pekerjaan'));
    }

    public function cetakBast(string $id)
    {
        $pekerjaan = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'pelaksana', 'vendor'])->findOrFail($id);
        
        if ($pekerjaan->progress != 100) {
            return redirect()->back()->withErrors('Pekerjaan belum mencapai 100% sehingga tidak dapat dicetak.');
        }

        if (!$pekerjaan->pelaksana || !$pekerjaan->vendor) {
            return redirect()->back()->withErrors('Data Pelaksana atau Vendor belum lengkap. Silakan lengkapi terlebih dahulu melalui form edit.');
        }

        // Generate nomor surat if not exist. Example: BAST-SKPD-ID/2026
        $nomorSurat = $pekerjaan->no_skpd ? 'BAST-' . $pekerjaan->no_skpd : 'BAST/SDA/' . str_pad($pekerjaan->id, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
        
        // Tanggal terbilang manual
        $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $tanggalSekarang = date('Y-m-d');
        $namaHari = $hari[date('l', strtotime($tanggalSekarang))];
        $tglStr = date('j', strtotime($tanggalSekarang));
        $blnStr = $bulan[(int)date('m', strtotime($tanggalSekarang))];
        $thnStr = date('Y', strtotime($tanggalSekarang));

        // Konversi angka ke teks (Sederhana untuk tanggal < 32 dan Tahun 2000an)
        $terbilangTgl = $this->terbilangAngka($tglStr);
        $terbilangThn = $this->terbilangAngka($thnStr);

        return view('admin.pekerjaan-sda.cetak-bast', compact('pekerjaan', 'nomorSurat', 'tanggalSekarang', 'namaHari', 'tglStr', 'blnStr', 'thnStr', 'terbilangTgl', 'terbilangThn'));
    }

    private function terbilangAngka($angka)
    {
        $angka = (int)$angka;
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        if ($angka < 12) return $huruf[$angka];
        if ($angka < 20) return $huruf[$angka - 10] . " Belas";
        if ($angka < 100) return $huruf[floor($angka / 10)] . " Puluh " . $huruf[$angka % 10];
        if ($angka < 200) return "Seratus " . $this->terbilangAngka($angka - 100);
        if ($angka < 1000) return $huruf[floor($angka / 100)] . " Ratus " . $this->terbilangAngka($angka % 100);
        if ($angka < 2000) return "Seribu " . $this->terbilangAngka($angka - 1000);
        if ($angka < 1000000) return $this->terbilangAngka(floor($angka / 1000)) . " Ribu " . $this->terbilangAngka($angka % 1000);
        return (string)$angka;
    }

    public function edit(string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        
        $kecamatanId = $this->getKecamatanId();
        
        if ($kecamatanId && $pekerjaan->id_kecamatan != $kecamatanId) {
            return redirect()->route('admin.pekerjaan-sda.index')->with('error', 'Akses ditolak.');
        }

        if ($kecamatanId) {
            $dewans = Dewan::where('id_kecamatan', $kecamatanId)->get();
            $kecamatans = Kecamatan::where('id', $kecamatanId)->get();
        } else {
            $dewans = Dewan::all();
            $kecamatans = Kecamatan::all();
        }
        
        $kelurahans = Kelurahan::all();
        $pelaksanas = Pelaksana::all();
        $vendors = Vendor::all();

        return view('admin.pekerjaan-sda.edit', compact('pekerjaan', 'dewans', 'kecamatans', 'kelurahans', 'pelaksanas', 'vendors'));
    }

    public function update(Request $request, string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        
        $validated = $request->validate([
            'sumber_data' => 'required|string',
            'no_skpd' => 'nullable|string',
            'tahun_monev' => 'nullable|numeric',
            'kode_tracking' => 'nullable|string',
            'id_dewan' => 'nullable|exists:dewans,id',
            'id_kecamatan' => 'nullable|exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'lingkup_kewenangan' => 'nullable|string',
            'kategori_pekerjaan' => 'nullable|string',
            'kategori_prioritas' => 'nullable|string',
            'volume_panjang' => 'nullable|string',
            'metode_pekerjaan' => 'nullable|string',
            'tgl_input' => 'nullable|date',
            'tgl_survei' => 'nullable|date',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'estimasi_tgl_realisasi' => 'nullable|date',
            'tahun_dikerjakan' => 'nullable|numeric',
            'progress' => 'nullable|numeric|min:0|max:100',
            'status_tindak_lanjut' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'photo.*' => 'nullable|image|max:2048',
            'id_pelaksana' => 'nullable|exists:pelaksanas,id',
            'id_vendor' => 'nullable|exists:vendors,id'
        ]);

        $fotoPaths = $pekerjaan->photo ? json_decode($pekerjaan->photo, true) : [];
        
        if ($request->has('hapus_foto')) {
            $hapusIndexes = $request->input('hapus_foto');
            rsort($hapusIndexes);
            foreach ($hapusIndexes as $index) {
                if (isset($fotoPaths[$index])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoPaths[$index]);
                    unset($fotoPaths[$index]);
                }
            }
            $fotoPaths = array_values($fotoPaths);
        }

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $path = $file->store('pekerjaan_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['photo'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;

        $pekerjaan->update($validated);

        // Sinkronisasi dengan Surat Permohonan jika ada
        if ($pekerjaan->suratPermohonan) {
            $pengirim = $pekerjaan->sumber_data;
            if ($pekerjaan->sumber_data == 'Reses' && $pekerjaan->dewan) {
                $pengirim = 'Dewan: ' . $pekerjaan->dewan->nama;
            } elseif ($pekerjaan->rincian_sumber_data) {
                $pengirim .= ' (' . $pekerjaan->rincian_sumber_data . ')';
            }

            $pekerjaan->suratPermohonan->update([
                'nomor_surat' => $pekerjaan->no_skpd,
                'dari' => $pengirim,
                'id_kecamatan' => $pekerjaan->id_kecamatan,
                'id_kelurahan' => $pekerjaan->id_kelurahan,
                'lokasi' => $pekerjaan->alamat,
                'deskripsi' => $pekerjaan->deskripsi,
            ]);
        }

        // Sinkronisasi status selesai ke reses jika progress 100
        if ($pekerjaan->id_survei_reses && $pekerjaan->progress == 100) {
            $survei = SurveiReses::find($pekerjaan->id_survei_reses);
            if ($survei) {
                $survei->update(['status' => 'Selesai']);
            }
        }

        if ($request->has('redirect_to') && $request->redirect_to) {
            return redirect($request->redirect_to)->with('success', 'Data Pekerjaan SDA berhasil diperbarui!');
        }

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Pekerjaan SDA berhasil diperbarui!');
    }

    public function mapView(Request $request)
    {
        $query = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan', 'surveiReses'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        if ($request->filled('sumber_data')) {
            $query->where('sumber_data', $request->sumber_data);
        }

        $pekerjaan = $query->get();
        $kecamatans = Kecamatan::all();
        return view('admin.pekerjaan-sda.map', compact('pekerjaan', 'kecamatans'));
    }

    public function destroy(string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        
        // Batalkan proses pada Surat Permohonan jika terkait
        $surat = SuratPermohonan::where('id_pekerjaan_sda', $id)->first();
        if ($surat) {
            $surat->update([
                'id_pekerjaan_sda' => null,
                'status' => 'Menunggu'
            ]);
        }

        $pekerjaan->delete();
        return back()->with('success', 'Data Pekerjaan dihapus. Usulan terkait telah dikembalikan ke status Menunggu!');
    }
}
