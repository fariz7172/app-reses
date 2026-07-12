<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PekerjaanSdaController;
use App\Http\Controllers\Admin\SuratPermohonanController;
use App\Http\Controllers\Admin\SurveiResesController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\UsulanMasyarakatController;
use App\Http\Controllers\Admin\FraksiController;
use App\Http\Controllers\Admin\PelaksanaController;
use App\Http\Controllers\Admin\VendorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke admin dashboard
Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected by Auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('survei-reses', SurveiResesController::class);
    Route::get('pekerjaan-sda/map', [PekerjaanSdaController::class, 'mapView'])->name('pekerjaan-sda.map');
    Route::get('pekerjaan-sda/{id}/cetak-bast', [PekerjaanSdaController::class, 'cetakBast'])->name('pekerjaan-sda.cetak-bast');
    Route::resource('pekerjaan-sda', PekerjaanSdaController::class);
    Route::resource('surat-permohonan', SuratPermohonanController::class);
    Route::post('usulan-masyarakat/{id}/terima', [UsulanMasyarakatController::class, 'terimaUsulan'])->name('usulan-masyarakat.terima');
    Route::resource('usulan-masyarakat', UsulanMasyarakatController::class);
    Route::resource('fraksi', FraksiController::class);
    Route::resource('pelaksana', PelaksanaController::class);
    Route::resource('vendor', VendorController::class);
    
    // Master Data Routes
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', [MasterDataController::class, 'index'])->name('index');
        Route::get('/dewan', [MasterDataController::class, 'dewan'])->name('dewan');
        Route::post('/dewan', [MasterDataController::class, 'storeDewan'])->name('dewan.store');
        Route::get('/dewan/{id}/edit', [MasterDataController::class, 'editDewan'])->name('dewan.edit');
        Route::put('/dewan/{id}', [MasterDataController::class, 'updateDewan'])->name('dewan.update');
        Route::delete('/dewan/{id}', [MasterDataController::class, 'destroyDewan'])->name('dewan.destroy');
        Route::get('/wilayah', [MasterDataController::class, 'wilayah'])->name('wilayah');
        Route::post('/wilayah/kecamatan', [MasterDataController::class, 'storeKecamatan'])->name('wilayah.kecamatan.store');
        Route::post('/wilayah/kelurahan', [MasterDataController::class, 'storeKelurahan'])->name('wilayah.kelurahan.store');
    });

    // Placeholders for sidebar links that don't have controllers yet
    Route::get('/analytics', function () { return view('admin.dashboard'); })->name('analytics');
    Route::get('/users', function () { return view('admin.dashboard'); })->name('users');
    Route::get('/reports', function () { return view('admin.dashboard'); })->name('reports');
    Route::get('/settings', function () { return view('admin.dashboard'); })->name('settings');

});
