<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Facades\Log;

class DatabaseBackupController extends Controller
{
    /**
     * Pastikan hanya role tertentu yang bisa mengakses method di controller ini
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $role = strtolower(auth()->user()->role ?? '');
            
            if (!in_array($role, ['super admin', 'admin', 'sudin'])) {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengunduh backup database.');
            }
            
            return $next($request);
        });
    }

    /**
     * Download backup database
     */
    public function download()
    {
        try {
            $fileName = 'backup_db_reses_' . date('Y_m_d_His') . '.sql';
            $storagePath = storage_path('app/' . $fileName);

            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD', '');
            $dbHost = env('DB_HOST', '127.0.0.1');

            // Menggunakan pure PHP dumper (ifsnop) agar tidak bergantung pada executable mysqldump di OS Windows/Linux
            $dump = new \Ifsnop\Mysqldump\Mysqldump("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
            $dump->start($storagePath);

            // Log aktivitas backup (jika menggunakan spatie/laravel-activitylog)
            if (class_exists(\Spatie\Activitylog\Facades\Activity::class)) {
                \Spatie\Activitylog\Facades\Activity::causedBy(auth()->user())
                    ->log('Mengunduh backup database (' . $fileName . ')');
            }

            // Kembalikan file untuk didownload lalu hapus setelah terkirim
            return response()->download($storagePath)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Database Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat backup database. Pesan error: ' . $e->getMessage());
        }
    }
}
