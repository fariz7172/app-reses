@extends('layouts.admin')

@section('title', 'Sistem Jejak Audit')
@section('page_title', 'Activity Log')

@section('content')
<div class="page-content">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" style="color: #1F6F5F;">Sistem Jejak Audit</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan aktivitas lengkap terkait perubahan data pada aplikasi.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h2 class="content-card-title">Log Aktivitas Terbaru</h2>
            <div class="flex gap-2">
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                    Total: {{ $logs->total() }} Log Tersimpan
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="15%">Waktu</th>
                        <th width="20%">Pelaku (Causer)</th>
                        <th width="15%">Aksi</th>
                        <th width="25%">Modul & ID</th>
                        <th width="25%">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span style="font-weight:500;">{{ $log->created_at->format('d M Y') }}</span>
                                </div>
                                <div style="font-size:12px;color:#6b7280;margin-left:24px;">
                                    {{ $log->created_at->format('H:i:s') }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(31,111,95,0.1);color:#1F6F5F;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                                        {{ strtoupper(substr($log->causer->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:#374151;">{{ $log->causer ? $log->causer->name : 'Sistem / Sistem Eksternal' }}</div>
                                        <div style="font-size:11px;color:#6b7280;">{{ $log->causer ? $log->causer->role : 'Guest' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($log->event === 'created')
                                    <span class="badge badge-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;margin-right:4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Dibuat
                                    </span>
                                @elseif($log->event === 'updated')
                                    <span class="badge badge-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;margin-right:4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Diubah
                                    </span>
                                @elseif($log->event === 'deleted')
                                    <span class="badge badge-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;margin-right:4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Dihapus
                                    </span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($log->event) }}</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:#374151;">{{ class_basename($log->subject_type) }}</div>
                                <div style="font-size:11px;color:#6b7280;display:flex;align-items:center;gap:4px;margin-top:2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                    ID: {{ $log->subject_id }}
                                </div>
                            </td>
                            <td>
                                @if(isset($log->properties['old']) || isset($log->properties['attributes']))
                                    <button type="button" class="btn btn-ghost" style="padding:4px 10px;font-size:12px;" onclick="document.getElementById('modal-{{ $log->id }}').style.display='flex'">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat Data
                                    </button>

                                    <!-- Modal (Native Basic) -->
                                    <div id="modal-{{ $log->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:20px;">
                                        <div style="background:#fff;border-radius:12px;width:100%;max-width:600px;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
                                            <div style="padding:16px 24px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#f9fafb;border-radius:12px 12px 0 0;">
                                                <h3 style="font-weight:600;font-size:16px;color:#111827;">Detail Perubahan Data</h3>
                                                <button onclick="document.getElementById('modal-{{ $log->id }}').style.display='none'" style="background:none;border:none;cursor:pointer;color:#6b7280;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                            <div style="padding:24px;overflow-y:auto;flex:1;">
                                                <pre style="background:#1f2937;color:#f3f4f6;padding:16px;border-radius:8px;font-size:13px;overflow-x:auto;font-family:monospace;white-space:pre-wrap;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                            <div style="padding:16px 24px;border-top:1px solid #e5e7eb;text-align:right;">
                                                <button onclick="document.getElementById('modal-{{ $log->id }}').style.display='none'" class="btn btn-ghost">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#9ca3af;font-size:12px;font-style:italic;">Tidak ada detail</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;color:#d1d5db;margin:0 auto 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p style="font-weight:500;color:#6b7280;font-size:15px;">Belum ada log aktivitas yang terekam.</p>
                                <p style="font-size:13px;color:#9ca3af;margin-top:4px;">Aktivitas baru akan otomatis muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($logs->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #f3f4f6;background:#f9fafb;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
