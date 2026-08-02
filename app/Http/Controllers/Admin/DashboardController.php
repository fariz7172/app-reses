<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PekerjaanSda;
use App\Models\SurveiReses;
use App\Models\SuratPermohonan;

class DashboardController extends Controller
{
    public function index()
    {
        // Kecamatan tidak punya akses ke Dashboard
        if (auth()->check() && auth()->user()->role === 'Kecamatan') {
            return redirect()->route('admin.surat-permohonan.index');
        }
        // 1. Hitung Statistik (Stat Cards)
        $totalPekerjaan = PekerjaanSda::count();
        $totalSurat = SuratPermohonan::count();
        $suratMenunggu = SuratPermohonan::where('status', 'Menunggu')->count();
        $totalReses = SurveiReses::count();

        // 2. Data Terbaru
        $pekerjaanTerbaru = PekerjaanSda::with(['kecamatan', 'kelurahan'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        $suratTerbaru = SuratPermohonan::orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalPekerjaan', 
            'totalSurat', 
            'suratMenunggu', 
            'totalReses', 
            'pekerjaanTerbaru', 
            'suratTerbaru'
        ));
    }
}
