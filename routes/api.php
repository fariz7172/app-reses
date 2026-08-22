<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\PekerjaanSdaApiController;
use App\Http\Controllers\Api\SuratPermohonanApiController;
use App\Http\Controllers\Api\SurveiResesApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for App-Reses
// Dilindungi oleh Sanctum (wajib bawa token) dan rate limiting (maksimal 60 request per menit)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Pekerjaan SDA
    Route::get('/pekerjaan-sda/map', [PekerjaanSdaApiController::class, 'getMapData']);
    Route::get('/pekerjaan-sda/{id}', [PekerjaanSdaApiController::class, 'getDetail']);

    // Surat Permohonan (Usulan Masyarakat)
    Route::get('/surat-permohonan/map', [SuratPermohonanApiController::class, 'getMapData']);
    Route::get('/surat-permohonan/{id}', [SuratPermohonanApiController::class, 'getDetail']);

    // Survei Reses (Usulan Dewan)
    Route::get('/survei-reses/map', [SurveiResesApiController::class, 'getMapData']);
    Route::get('/survei-reses/{id}', [SurveiResesApiController::class, 'getDetail']);
});
