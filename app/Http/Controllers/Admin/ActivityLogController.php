<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        // Hanya Super Admin yang diizinkan
        $this->middleware(function ($request, $next) {
            $role = strtolower(auth()->user()->role ?? '');
            if (!in_array($role, ['super admin', 'admin'])) {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Mengambil log aktivitas dari yang terbaru
        $logs = Activity::with('causer')->latest()->paginate(20);
        return view('admin.activity-log.index', compact('logs'));
    }
}
