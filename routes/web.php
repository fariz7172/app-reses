<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PekerjaanSdaController;
use App\Http\Controllers\Admin\SuratPermohonanController;
use App\Http\Controllers\Admin\SurveiResesController;
use App\Http\Controllers\Admin\MasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke admin dashboard
Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Dummy logout route (development)
Route::post('/logout', function () {
    return redirect('/admin/dashboard');
})->name('logout');

// Admin Routes (tanpa auth untuk development/preview)
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('survei-reses', SurveiResesController::class);
    Route::get('pekerjaan-sda/map', [PekerjaanSdaController::class, 'mapView'])->name('pekerjaan-sda.map');
    Route::resource('pekerjaan-sda', PekerjaanSdaController::class);
    Route::resource('surat-permohonan', SuratPermohonanController::class);
    
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
