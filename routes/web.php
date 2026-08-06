<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PekerjaanSdaController;
use App\Http\Controllers\Admin\SuratPermohonanController;
use App\Http\Controllers\Admin\SurveiResesController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\FraksiController;
use App\Http\Controllers\Admin\PelaksanaController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;

// Setup Route (Hanya untuk dijalankan sekali di server)
Route::get('/setup-server-data', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => 'RoleUserSeeder', '--force' => true]);
        return "Berhasil! Kolom Role dan 9 Akun (superadmin, sudin, kecamatan, dll) telah ditambahkan ke database Server.";
    } catch (\Exception $e) {
        return "Gagal: " . $e->getMessage();
    }
});

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

    Route::get('survei-reses/export-excel', [SurveiResesController::class, 'exportExcel'])->name('survei-reses.export-excel');
    Route::post('survei-reses/import-excel', [SurveiResesController::class, 'importExcel'])->name('survei-reses.import-excel');
    Route::resource('survei-reses', SurveiResesController::class);
    Route::get('pekerjaan-sda/export-excel', [PekerjaanSdaController::class, 'exportExcel'])->name('pekerjaan-sda.export-excel');
    Route::get('pekerjaan-sda/map', [PekerjaanSdaController::class, 'mapView'])->name('pekerjaan-sda.map');
    Route::get('pekerjaan-sda/{id}/cetak-bast', [PekerjaanSdaController::class, 'cetakBast'])->name('pekerjaan-sda.cetak-bast');
    Route::resource('pekerjaan-sda', PekerjaanSdaController::class);
    Route::get('surat-permohonan/export-excel', [SuratPermohonanController::class, 'exportExcel'])->name('surat-permohonan.export-excel');
    Route::post('surat-permohonan/import-excel', [SuratPermohonanController::class, 'importExcel'])->name('surat-permohonan.import-excel');
    Route::post('surat-permohonan/import-earsip', [SuratPermohonanController::class, 'importEarsip'])->name('surat-permohonan.import-earsip');
    Route::post('surat-permohonan/{id}/proses', [SuratPermohonanController::class, 'prosesPekerjaan'])->name('surat-permohonan.proses');
    Route::resource('surat-permohonan', SuratPermohonanController::class);
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
    Route::get('/analytics', function () { return redirect()->route('admin.dashboard'); })->name('analytics');
    Route::get('/reports', function () { return redirect()->route('admin.dashboard'); })->name('reports');
    
    // User Management (Settings)
    Route::resource('users', UserController::class);

});
