<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PekerjaanSda;
use App\Models\UsulanMasyarakat;
use App\Models\SurveiReses;
use App\Models\SuratPermohonan;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik (Stat Cards)
        $totalPekerjaan = PekerjaanSda::count();
        $totalUsulan = UsulanMasyarakat::count();
        $usulanMenunggu = UsulanMasyarakat::where('status', 'Menunggu')->count();
        $totalReses = SurveiReses::count();
        $totalSurat = SuratPermohonan::count();

        // 2. Data Terbaru
        $pekerjaanTerbaru = PekerjaanSda::with(['kecamatan', 'kelurahan'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        $usulanTerbaru = UsulanMasyarakat::orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalPekerjaan', 
            'totalUsulan', 
            'usulanMenunggu', 
            'totalReses', 
            'totalSurat', 
            'pekerjaanTerbaru', 
            'usulanTerbaru'
        ));
    }
}
