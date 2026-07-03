@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- ====================================================
     WELCOME BANNER
==================================================== --}}
<div style="background:linear-gradient(135deg,#1F6F5F 0%,#76b38e 100%);
            border-radius:16px;padding:28px 28px;margin-bottom:20px;
            position:relative;overflow:hidden;box-shadow:0 4px 20px rgba(159,203,173,0.4);">
    {{-- Decorative circles --}}
    <div style="position:absolute;top:-30px;right:-30px;width:130px;height:130px;background:rgba(255,255,255,0.12);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-20px;right:60px;width:80px;height:80px;background:rgba(21,145,220,0.2);border-radius:50%;"></div>
    <div style="position:absolute;top:20px;right:160px;width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>

    <div style="position:relative;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
        <div>
            <p style="color:rgba(255,255,255,0.8);font-size:13px;font-weight:500;margin:0 0 4px;">🌿 Selamat datang kembali,</p>
            <h2 style="color:#fff;font-size:22px;font-weight:700;margin:0 0 6px;letter-spacing:-0.02em;">
                {{ auth()->user()->name ?? 'Administrator' }}
            </h2>
            <p style="color:rgba(255,255,255,0.7);font-size:13px;margin:0;">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} — Semua sistem berjalan normal ✓
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="#" class="btn btn-ghost"
               style="background:rgba(255,255,255,0.9);color:#3f7a58;border:none;font-size:13px;padding:8px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Unduh Laporan
            </a>
            <a href="#" class="btn btn-accent" style="font-size:13px;padding:8px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Baru
            </a>
        </div>
    </div>
</div>

{{-- ====================================================
     STAT CARDS — 4 columns responsive
==================================================== --}}
<div class="grid-4col mb-16">

    {{-- Card 1 --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#e8f4fd;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#1591DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <span class="badge badge-info">+12.5%</span>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Total Pengguna</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">2,847</p>
        <p style="font-size:11px;color:#16a34a;display:flex;align-items:center;gap:4px;margin:0;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            +342 bulan ini
        </p>
    </div>

    {{-- Card 2 --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#dceee2;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#3f7a58;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
            </div>
            <span class="badge badge-success">Aktif</span>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Total Produk</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">1,204</p>
        <p style="font-size:11px;color:#3f7a58;display:flex;align-items:center;gap:4px;margin:0;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            +58 produk baru
        </p>
    </div>

    {{-- Card 3 --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#fef9c3;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#a16207;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
            </div>
            <span class="badge badge-warning">5 Pending</span>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Total Pesanan</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">586</p>
        <p style="font-size:11px;color:#a16207;display:flex;align-items:center;gap:4px;margin:0;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            +87 bulan ini
        </p>
    </div>

    {{-- Card 4 --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="badge badge-success">+8.2%</span>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Pendapatan</p>
        <p style="font-size:22px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">Rp 48,2 Jt</p>
        <p style="font-size:11px;color:#16a34a;display:flex;align-items:center;gap:4px;margin:0;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            vs bulan lalu
        </p>
    </div>

</div>

{{-- ====================================================
     CHART + CATEGORIES
==================================================== --}}
<div class="grid-3col-main mb-16">

    {{-- Bar Chart --}}
    <div class="content-card">
        <div class="content-card-header">
            <div>
                <p class="content-card-title">Grafik Penjualan</p>
                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">Performa 7 hari terakhir</p>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="btn btn-ghost" style="font-size:12px;padding:5px 12px;">Minggu</button>
                <button class="btn btn-primary" style="font-size:12px;padding:5px 12px;">Bulan</button>
            </div>
        </div>
        <div style="padding:20px;">
            @php
            $bars = [
                ['day'=>'Sen','pct'=>45,'val'=>'Rp 4,5Jt','top'=>false],
                ['day'=>'Sel','pct'=>72,'val'=>'Rp 7,2Jt','top'=>false],
                ['day'=>'Rab','pct'=>58,'val'=>'Rp 5,8Jt','top'=>false],
                ['day'=>'Kam','pct'=>90,'val'=>'Rp 9,0Jt','top'=>true],
                ['day'=>'Jum','pct'=>65,'val'=>'Rp 6,5Jt','top'=>false],
                ['day'=>'Sab','pct'=>80,'val'=>'Rp 8,0Jt','top'=>false],
                ['day'=>'Min','pct'=>38,'val'=>'Rp 3,8Jt','top'=>false],
            ];
            @endphp
            {{-- Chart bars --}}
            <div style="display:flex;align-items:flex-end;gap:8px;height:140px;margin-bottom:8px;">
                @foreach($bars as $b)
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;">
                    <div style="flex:1;width:100%;display:flex;align-items:flex-end;">
                        <div class="chart-bar-item"
                             data-val="{{ $b['val'] }}"
                             style="width:100%;height:{{ $b['pct'] }}%;
                                    background:{{ $b['top'] ? '#1591DC' : '#1F6F5F' }};
                                    border-radius:6px 6px 0 0;
                                    cursor:pointer;position:relative;
                                    transition:opacity 0.18s;"
                             onmouseover="showBarTooltip(this)"
                             onmouseout="hideBarTooltip(this)">
                            <div class="bar-tooltip"
                                 style="position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);
                                        background:#1f2937;color:#fff;font-size:10px;font-weight:600;
                                        padding:3px 7px;border-radius:6px;white-space:nowrap;
                                        opacity:0;pointer-events:none;transition:opacity 0.18s;">
                                {{ $b['val'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Day labels --}}
            <div style="display:flex;gap:8px;">
                @foreach($bars as $b)
                <div style="flex:1;text-align:center;font-size:11px;color:#9ca3af;font-weight:500;">{{ $b['day'] }}</div>
                @endforeach
            </div>
            {{-- Legend --}}
            <div style="display:flex;gap:16px;margin-top:16px;padding-top:14px;border-top:1px solid #f3f4f6;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:10px;height:10px;border-radius:3px;background:#1F6F5F;"></div>
                    <span style="font-size:12px;color:#6b7280;">Penjualan</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:10px;height:10px;border-radius:3px;background:#1591DC;"></div>
                    <span style="font-size:12px;color:#6b7280;">Tertinggi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="content-card">
        <div class="content-card-header">
            <p class="content-card-title">Kategori Teratas</p>
            <a href="#" style="font-size:12px;font-weight:600;color:#1591DC;text-decoration:none;">Semua →</a>
        </div>
        <div style="padding:16px 20px;">
            @php
            $cats = [
                ['name'=>'Elektronik', 'pct'=>78,'val'=>'Rp 18,4Jt','color'=>'#1591DC'],
                ['name'=>'Fashion',    'pct'=>62,'val'=>'Rp 12,1Jt','color'=>'#1F6F5F'],
                ['name'=>'Makanan',    'pct'=>48,'val'=>'Rp 9,3Jt', 'color'=>'#f59e0b'],
                ['name'=>'Olahraga',  'pct'=>35,'val'=>'Rp 6,8Jt', 'color'=>'#8b5cf6'],
                ['name'=>'Kecantikan','pct'=>21,'val'=>'Rp 4,1Jt', 'color'=>'#ec4899'],
            ];
            @endphp
            @foreach($cats as $cat)
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <span style="font-size:13px;font-weight:500;color:#374151;">{{ $cat['name'] }}</span>
                    <span style="font-size:12px;font-weight:600;color:#6b7280;">{{ $cat['pct'] }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width:0%;background:{{ $cat['color'] }};" data-width="{{ $cat['pct'] }}%"></div>
                </div>
                <p style="font-size:11px;color:#9ca3af;margin:4px 0 0;">{{ $cat['val'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ====================================================
     TABLE + ACTIVITY
==================================================== --}}
<div class="grid-3col-bottom mb-16">

    {{-- Orders Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div>
                <p class="content-card-title">Pesanan Terbaru</p>
                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">5 transaksi terakhir</p>
            </div>
            <a href="{{ url('/admin/orders') }}" class="btn btn-ghost" style="font-size:12px;padding:5px 12px;">
                Lihat Semua →
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $orders = [
                        ['id'=>'#ORD-4521','name'=>'Budi Santoso',  'av'=>'B','prod'=>'Laptop Acer',    'total'=>'Rp 8.500.000','st'=>'Selesai','b'=>'badge-success'],
                        ['id'=>'#ORD-4520','name'=>'Siti Rahayu',   'av'=>'S','prod'=>'Headphone Sony', 'total'=>'Rp 1.250.000','st'=>'Proses', 'b'=>'badge-info'],
                        ['id'=>'#ORD-4519','name'=>'Ahmad Fauzi',   'av'=>'A','prod'=>'Smartphone Oppo','total'=>'Rp 3.750.000','st'=>'Pending','b'=>'badge-warning'],
                        ['id'=>'#ORD-4518','name'=>'Dewi Lestari',  'av'=>'D','prod'=>'Sepatu Nike',    'total'=>'Rp 890.000',  'st'=>'Selesai','b'=>'badge-success'],
                        ['id'=>'#ORD-4517','name'=>'Rudi Hartono',  'av'=>'R','prod'=>'Kemeja Polo',    'total'=>'Rp 350.000',  'st'=>'Batal',  'b'=>'badge-danger'],
                    ];
                    @endphp
                    @foreach($orders as $o)
                    <tr>
                        <td><span style="font-family:monospace;font-size:12px;font-weight:600;color:#1591DC;">{{ $o['id'] }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:8px;background:#dceee2;display:flex;align-items:center;justify-content:center;color:#3f7a58;font-size:12px;font-weight:700;flex-shrink:0;">{{ $o['av'] }}</div>
                                <span style="font-weight:500;color:#374151;font-size:13px;">{{ $o['name'] }}</span>
                            </div>
                        </td>
                        <td style="color:#6b7280;font-size:13px;">{{ $o['prod'] }}</td>
                        <td style="font-weight:600;color:#1f2937;font-size:13px;white-space:nowrap;">{{ $o['total'] }}</td>
                        <td><span class="badge {{ $o['b'] }}">{{ $o['st'] }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:4px;">
                                <button style="width:28px;height:28px;border-radius:8px;border:none;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#9ca3af;transition:background 0.15s;"
                                        onmouseover="this.style.background='#e8f4fd';this.style.color='#1591DC'"
                                        onmouseout="this.style.background='transparent';this.style.color='#9ca3af'">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button style="width:28px;height:28px;border-radius:8px;border:none;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#9ca3af;transition:background 0.15s;"
                                        onmouseover="this.style.background='#dceee2';this.style.color='#3f7a58'"
                                        onmouseout="this.style.background='transparent';this.style.color='#9ca3af'">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="content-card">
        <div class="content-card-header">
            <p class="content-card-title">Aktivitas Terbaru</p>
            <button style="width:28px;height:28px;border-radius:8px;border:none;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#9ca3af;"
                    onmouseover="this.style.background='#F1F7D4'" onmouseout="this.style.background='transparent'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
        <div style="padding:16px 20px;">
            @php
            $acts = [
                ['bg'=>'#e8f4fd','ic'=>'#1591DC','desc'=>'Pengguna baru <strong>Rina Marliana</strong> mendaftar',    'time'=>'5 menit lalu'],
                ['bg'=>'#dceee2','ic'=>'#3f7a58','desc'=>'Pesanan <strong>#ORD-4521</strong> telah diselesaikan',      'time'=>'18 menit lalu'],
                ['bg'=>'#fef9c3','ic'=>'#a16207','desc'=>'Stok <strong>Headphone Sony</strong> tinggal 3 unit',        'time'=>'45 menit lalu'],
                ['bg'=>'#ede9fe','ic'=>'#7c3aed','desc'=>'Produk <strong>Laptop Acer</strong> diperbarui',             'time'=>'2 jam lalu'],
                ['bg'=>'#dcfce7','ic'=>'#16a34a','desc'=>'Laporan Juni berhasil diunduh',                              'time'=>'3 jam lalu'],
                ['bg'=>'#fef2f2','ic'=>'#dc2626','desc'=>'Pesanan <strong>#ORD-4500</strong> dibatalkan',              'time'=>'5 jam lalu'],
            ];
            @endphp
            @foreach($acts as $i => $a)
            <div style="display:flex;align-items:flex-start;gap:10px;{{ !$loop->last ? 'padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid #f9fafb;' : '' }}">
                <div style="width:30px;height:30px;border-radius:8px;background:{{ $a['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:{{ $a['ic'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12.5px;color:#4b5563;margin:0;line-height:1.5;">{!! $a['desc'] !!}</p>
                    <p style="font-size:11px;color:#9ca3af;margin:3px 0 0;display:flex;align-items:center;gap:4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $a['time'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ====================================================
     QUICK ACTIONS
==================================================== --}}
<div class="content-card">
    <div class="content-card-header">
        <p class="content-card-title">Aksi Cepat</p>
    </div>
    <div style="padding:16px 20px;">
        <div class="grid-4actions">
            @php
            $qacts = [
                ['bg'=>'#e8f4fd','hbg'=>'#1591DC','ic'=>'#1591DC','label'=>'Tambah Produk',   'path'=>'M12 4v16m8-8H4'],
                ['bg'=>'#dceee2','hbg'=>'#1F6F5F','ic'=>'#3f7a58','label'=>'Tambah User',     'path'=>'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                ['bg'=>'#fef9c3','hbg'=>'#f59e0b','ic'=>'#a16207','label'=>'Buat Laporan',    'path'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['bg'=>'#dcfce7','hbg'=>'#22c55e','ic'=>'#16a34a','label'=>'Catat Transaksi', 'path'=>'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp
            @foreach($qacts as $q)
            <button style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;
                           border-radius:12px;border:none;background:{{ $q['bg'] }};cursor:pointer;
                           transition:all 0.2s;font-family:inherit;"
                    onmouseover="this.style.background='{{ $q['hbg'] }}';this.style.color='white';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';this.querySelector('.qa-icon').style.color='white';this.querySelector('.qa-label').style.color='white';"
                    onmouseout="this.style.background='{{ $q['bg'] }}';this.style.transform='';this.style.boxShadow='';this.querySelector('.qa-icon').style.color='{{ $q['ic'] }}';this.querySelector('.qa-label').style.color='#374151';">
                <svg xmlns="http://www.w3.org/2000/svg" class="qa-icon" style="width:22px;height:22px;color:{{ $q['ic'] }};transition:color 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $q['path'] }}"/>
                </svg>
                <span class="qa-label" style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;line-height:1.3;transition:color 0.2s;">{{ $q['label'] }}</span>
            </button>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Animate progress bars
    document.addEventListener('DOMContentLoaded', function() {
        const fills = document.querySelectorAll('.progress-fill[data-width]');
        setTimeout(() => {
            fills.forEach(f => { f.style.width = f.getAttribute('data-width'); });
        }, 300);
    });

    // Bar chart tooltips
    function showBarTooltip(el) {
        el.style.opacity = '0.85';
        el.querySelector('.bar-tooltip').style.opacity = '1';
    }
    function hideBarTooltip(el) {
        el.style.opacity = '1';
        el.querySelector('.bar-tooltip').style.opacity = '0';
    }
</script>
@endpush
