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
    public function index()
    {
        $pekerjaan = PekerjaanSda::with(['dewan', 'kecamatan', 'kelurahan'])->latest()->get();
        return view('admin.pekerjaan-sda.index', compact('pekerjaan'));
    }

    public function create(Request $request)
    {
        $survei_id = $request->query('survei_id');
        $survei_terpilih = null;
        
        if ($survei_id) {
            $survei_terpilih = SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->find($survei_id);
        }

        // Ambil data survei reses yang belum di eskalasi
        $survei_reses_list = SurveiReses::whereDoesntHave('pekerjaanSda')->get();
        
        $dewans = Dewan::all();
        $kecamatans = Kecamatan::all();
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
        $dewans = Dewan::all();
        $kecamatans = Kecamatan::all();
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

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Pekerjaan SDA berhasil diperbarui!');
    }

    public function mapView()
    {
        $pekerjaan = PekerjaanSda::whereNotNull('latitude')->whereNotNull('longitude')->get();
        return view('admin.pekerjaan-sda.map', compact('pekerjaan'));
    }

    public function destroy(string $id)
    {
        $pekerjaan = PekerjaanSda::findOrFail($id);
        $pekerjaan->delete();
        return back()->with('success', 'Data Pekerjaan dihapus!');
    }
}
