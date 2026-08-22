<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveiResesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->latest();
        
        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_reses', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%")
                  ->orWhere('permintaan', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('dewan', function($qDewan) use ($search) {
                      $qDewan->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $survei = $query->paginate(10);
        $survei->appends($request->all());
        
        return view('admin.survei-reses.index', compact('survei'));
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = \App\Models\SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->latest();
        
        $kecamatanId = $this->getKecamatanId();
        if ($kecamatanId) {
            $query->where('id_kecamatan', $kecamatanId);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_reses', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%")
                  ->orWhere('permintaan', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('dewan', function($qDewan) use ($search) {
                      $qDewan->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $survei = $query->get();
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SurveiResesExport($survei), 
            'Data_Survei_Reses_' . date('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Import data from Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ResesImport, $request->file('file_excel'));
            return redirect()->route('admin.survei-reses.index')->with('success', 'Data Reses berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('admin.survei-reses.index')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatanId = $this->getKecamatanId();
        
        if ($kecamatanId) {
            $dewans = \App\Models\Dewan::where('id_kecamatan', $kecamatanId)->get();
            $kecamatans = \App\Models\Kecamatan::where('id', $kecamatanId)->get();
        } else {
            $dewans = \App\Models\Dewan::all();
            $kecamatans = \App\Models\Kecamatan::all();
        }
        $kelurahans = \App\Models\Kelurahan::all(); // Alternatively, loaded via AJAX
        
        return view('admin.survei-reses.create', compact('dewans', 'kecamatans', 'kelurahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dewan' => 'required|exists:dewans,id',
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'id_kelurahan' => 'required|exists:kelurahans,id',
            'tanggal_reses' => 'required|date',
            'alamat' => 'required|string',
            'permintaan' => 'required|string',
            'keterangan' => 'nullable|string',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'status' => 'required|string',
            'foto.*' => 'nullable|image|max:2048' // Validasi untuk setiap foto (max 2MB)
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('reses_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['foto'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;
        
        // Simpan keluhan yang sementara kita ambil dari permintaan
        $validated['keluhan'] = $validated['permintaan'];

        $survei = \App\Models\SurveiReses::create($validated);

        // Auto-Generate Pekerjaan SDA
        $pekerjaan = \App\Models\PekerjaanSda::create([
            'id_survei_reses' => $survei->id,
            'id_dewan' => $survei->id_dewan,
            'sumber_data' => 'Hasil Reses',
            'id_kecamatan' => $survei->id_kecamatan,
            'id_kelurahan' => $survei->id_kelurahan,
            'alamat' => $survei->alamat,
            'deskripsi' => $survei->keluhan . ' / ' . $survei->permintaan,
            'tgl_input' => date('Y-m-d'),
            'tahun_monev' => date('Y'),
        ]);

        // Auto-Generate Surat Permohonan
        $pengirim = 'Dewan';
        if ($survei->dewan) {
            $pengirim = 'Dewan: ' . $survei->dewan->nama;
        }

        \App\Models\SuratPermohonan::create([
            'id_pekerjaan_sda' => $pekerjaan->id,
            'tanggal' => date('Y-m-d'),
            'status' => 'Menunggu',
            'dari' => $pengirim,
            'id_kecamatan' => $pekerjaan->id_kecamatan,
            'id_kelurahan' => $pekerjaan->id_kelurahan,
            'lokasi' => $pekerjaan->alamat,
            'deskripsi' => $pekerjaan->deskripsi,
        ]);

        return redirect()->route('admin.pekerjaan-sda.index')->with('success', 'Data Survei Reses berhasil disimpan dan otomatis masuk ke Pekerjaan SDA!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $survei = \App\Models\SurveiReses::with(['dewan', 'kecamatan', 'kelurahan'])->findOrFail($id);
        return view('admin.survei-reses.show', compact('survei'));
    }

    public function edit(string $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $survei = \App\Models\SurveiReses::findOrFail($id);
        
        $kecamatanId = $this->getKecamatanId();
        
        if ($kecamatanId) {
            $dewans = \App\Models\Dewan::where('id_kecamatan', $kecamatanId)->get();
            $kecamatans = \App\Models\Kecamatan::where('id', $kecamatanId)->get();
            
            // Keamanan: cegah edit data yang bukan milik kecamatannya
            if ($survei->id_kecamatan != $kecamatanId) {
                return redirect()->route('admin.survei-reses.index')->with('error', 'Akses ditolak.');
            }
        } else {
            $dewans = \App\Models\Dewan::all();
            $kecamatans = \App\Models\Kecamatan::all();
        }
        $kelurahans = \App\Models\Kelurahan::all();
        
        return view('admin.survei-reses.edit', compact('survei', 'dewans', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $survei = \App\Models\SurveiReses::findOrFail($id);
        
        $kecamatanId = $this->getKecamatanId();
        
        // Proteksi IDOR: Cegah user dari kecamatan lain melakukan update
        if ($kecamatanId && $survei->id_kecamatan != $kecamatanId) {
            return redirect()->route('admin.survei-reses.index')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'id_dewan' => 'required|exists:dewans,id',
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'id_kelurahan' => 'required|exists:kelurahans,id',
            'tanggal_reses' => 'required|date',
            'alamat' => 'required|string',
            'permintaan' => 'required|string',
            'keterangan' => 'nullable|string',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'status' => 'required|string',
            'foto.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = $survei->foto ? json_decode($survei->foto, true) : [];
        
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

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('reses_photos', 'public');
                $fotoPaths[] = $path;
            }
        }
        $validated['foto'] = count($fotoPaths) > 0 ? json_encode($fotoPaths) : null;
        $validated['keluhan'] = $validated['permintaan'];

        $survei->update($validated);

        return redirect()->route('admin.survei-reses.index')->with('success', 'Data Survei Reses berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString(hex2bin($id));
        } catch (\Exception $e) {
            abort(404, 'URL Tidak Valid');
        }

        $role = strtolower(auth()->user()->role ?? '');
        if (!in_array($role, ['super admin', 'sudin'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data secara permanen.');
        }

        $survei = \App\Models\SurveiReses::findOrFail($id);
        $survei->delete();
        return back()->with('success', 'Data Survei Reses berhasil dihapus!');
    }
}
