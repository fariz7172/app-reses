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

    {{-- Card 1: Pekerjaan SDA --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#e8f4fd;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#1591DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Total Pekerjaan SDA</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">{{ number_format($totalPekerjaan, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:#16a34a;display:flex;align-items:center;gap:4px;margin:0;">
            Total titik pekerjaan terdaftar
        </p>
    </div>

    {{-- Card 2: Usulan Masyarakat --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#dceee2;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#3f7a58;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            @if($usulanMenunggu > 0)
                <span class="badge badge-warning">{{ $usulanMenunggu }} Menunggu</span>
            @endif
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Usulan Masyarakat</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">{{ number_format($totalUsulan, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:#3f7a58;display:flex;align-items:center;gap:4px;margin:0;">
            Total aduan masyarakat
        </p>
    </div>

    {{-- Card 3: Survei Reses --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#fef9c3;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#a16207;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
            </div>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Hasil Survei Reses</p>
        <p style="font-size:26px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">{{ number_format($totalReses, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:#a16207;display:flex;align-items:center;gap:4px;margin:0;">
            Data hasil reses dewan
        </p>
    </div>

    {{-- Card 4: Surat Permohonan --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 4px;">Surat Permohonan</p>
        <p style="font-size:22px;font-weight:700;color:#1f2937;margin:0 0 6px;line-height:1;">{{ number_format($totalSurat, 0, ',', '.') }}</p>
        <p style="font-size:11px;color:#16a34a;display:flex;align-items:center;gap:4px;margin:0;">
            Surat permohonan survei
        </p>
    </div>

</div>

{{-- CHATS & KATEGORI (Dihapus karena digantikan data real SDA) --}}

{{-- ====================================================
     TABLE + ACTIVITY
==================================================== --}}
<div class="grid-3col-bottom mb-16">

    {{-- Pekerjaan SDA Terbaru Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div>
                <p class="content-card-title">Pekerjaan SDA Terbaru</p>
                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">5 data terakhir ditambahkan</p>
            </div>
            <a href="{{ route('admin.pekerjaan-sda.index') }}" class="btn btn-ghost" style="font-size:12px;padding:5px 12px;">
                Lihat Semua →
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tgl Input</th>
                        <th>Kecamatan</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status / Progress</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pekerjaanTerbaru as $p)
                    <tr>
                        <td><span style="font-family:monospace;font-size:12px;font-weight:600;color:#1591DC;">{{ date('d/m/Y', strtotime($p->tgl_input)) }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:8px;background:#dceee2;display:flex;align-items:center;justify-content:center;color:#3f7a58;font-size:12px;font-weight:700;flex-shrink:0;">
                                    {{ substr($p->kecamatan ? $p->kecamatan->nama_kecamatan : 'X', 0, 1) }}
                                </div>
                                <span style="font-weight:500;color:#374151;font-size:13px;">{{ $p->kecamatan ? $p->kecamatan->nama_kecamatan : '-' }}</span>
                            </div>
                        </td>
                        <td style="color:#6b7280;font-size:13px;">{{ $p->kategori_pekerjaan ?: '-' }}</td>
                        <td style="font-weight:600;color:#1f2937;font-size:12px;">{{ Str::limit($p->alamat, 25) }}</td>
                        <td>
                            <span class="badge {{ $p->progress == 100 ? 'badge-success' : 'badge-warning' }}">
                                {{ $p->progress ?: 0 }}%
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:4px;">
                                <a href="{{ route('admin.pekerjaan-sda.show', $p->id) }}" style="width:28px;height:28px;border-radius:8px;background:transparent;display:flex;align-items:center;justify-content:center;color:#9ca3af;transition:background 0.15s;" onmouseover="this.style.background='#e8f4fd';this.style.color='#1591DC'" onmouseout="this.style.background='transparent';this.style.color='#9ca3af'">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.pekerjaan-sda.edit', $p->id) }}" style="width:28px;height:28px;border-radius:8px;background:transparent;display:flex;align-items:center;justify-content:center;color:#9ca3af;transition:background 0.15s;" onmouseover="this.style.background='#dceee2';this.style.color='#3f7a58'" onmouseout="this.style.background='transparent';this.style.color='#9ca3af'">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($pekerjaanTerbaru->count() == 0)
                    <tr>
                        <td colspan="6" style="text-align:center;color:#9ca3af;padding:20px;">Belum ada data Pekerjaan SDA</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Usulan Masyarakat Feed --}}
    <div class="content-card">
        <div class="content-card-header">
            <p class="content-card-title">Usulan Masuk Terbaru</p>
            <a href="{{ route('admin.usulan-masyarakat.index') }}" style="width:28px;height:28px;border-radius:8px;background:transparent;display:flex;align-items:center;justify-content:center;color:#9ca3af;" onmouseover="this.style.background='#F1F7D4'" onmouseout="this.style.background='transparent'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
        </div>
        <div style="padding:16px 20px;">
            @foreach($usulanTerbaru as $ut)
            <div style="display:flex;align-items:flex-start;gap:10px;{{ !$loop->last ? 'padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid #f9fafb;' : '' }}">
                <div style="width:30px;height:30px;border-radius:8px;background:{{ $ut->status == 'Menunggu' ? '#fef9c3' : '#dceee2' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:{{ $ut->status == 'Menunggu' ? '#a16207' : '#3f7a58' }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12.5px;color:#4b5563;margin:0;line-height:1.5;">Usulan dari <strong>{{ $ut->nama_pengusul }}</strong> ({{ Str::limit($ut->deskripsi_usulan, 30) }})</p>
                    <p style="font-size:11px;color:#9ca3af;margin:3px 0 0;display:flex;align-items:center;gap:4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $ut->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
            @if($usulanTerbaru->count() == 0)
                <p style="font-size:12px;color:#9ca3af;text-align:center;">Belum ada usulan masyarakat.</p>
            @endif
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
            <a href="{{ route('admin.pekerjaan-sda.create') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;
                           border-radius:12px;border:none;background:#e8f4fd;cursor:pointer;
                           transition:all 0.2s;font-family:inherit;text-decoration:none;"
                    onmouseover="this.style.background='#1591DC';this.style.color='white';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';this.querySelector('.qa-icon').style.color='white';this.querySelector('.qa-label').style.color='white';"
                    onmouseout="this.style.background='#e8f4fd';this.style.transform='';this.style.boxShadow='';this.querySelector('.qa-icon').style.color='#1591DC';this.querySelector('.qa-label').style.color='#374151';">
                <svg xmlns="http://www.w3.org/2000/svg" class="qa-icon" style="width:22px;height:22px;color:#1591DC;transition:color 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="qa-label" style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;line-height:1.3;transition:color 0.2s;">Tambah Pekerjaan SDA</span>
            </a>
            
            <a href="{{ route('admin.usulan-masyarakat.create') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;
                           border-radius:12px;border:none;background:#dceee2;cursor:pointer;
                           transition:all 0.2s;font-family:inherit;text-decoration:none;"
                    onmouseover="this.style.background='#1F6F5F';this.style.color='white';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';this.querySelector('.qa-icon').style.color='white';this.querySelector('.qa-label').style.color='white';"
                    onmouseout="this.style.background='#dceee2';this.style.transform='';this.style.boxShadow='';this.querySelector('.qa-icon').style.color='#3f7a58';this.querySelector('.qa-label').style.color='#374151';">
                <svg xmlns="http://www.w3.org/2000/svg" class="qa-icon" style="width:22px;height:22px;color:#3f7a58;transition:color 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="qa-label" style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;line-height:1.3;transition:color 0.2s;">Tambah Usulan</span>
            </a>

            <a href="{{ route('admin.surat-permohonan.index') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;
                           border-radius:12px;border:none;background:#fef9c3;cursor:pointer;
                           transition:all 0.2s;font-family:inherit;text-decoration:none;"
                    onmouseover="this.style.background='#f59e0b';this.style.color='white';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';this.querySelector('.qa-icon').style.color='white';this.querySelector('.qa-label').style.color='white';"
                    onmouseout="this.style.background='#fef9c3';this.style.transform='';this.style.boxShadow='';this.querySelector('.qa-icon').style.color='#a16207';this.querySelector('.qa-label').style.color='#374151';">
                <svg xmlns="http://www.w3.org/2000/svg" class="qa-icon" style="width:22px;height:22px;color:#a16207;transition:color 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="qa-label" style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;line-height:1.3;transition:color 0.2s;">Daftar Surat</span>
            </a>

            <a href="{{ route('admin.pekerjaan-sda.map') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;
                           border-radius:12px;border:none;background:#dcfce7;cursor:pointer;
                           transition:all 0.2s;font-family:inherit;text-decoration:none;"
                    onmouseover="this.style.background='#22c55e';this.style.color='white';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';this.querySelector('.qa-icon').style.color='white';this.querySelector('.qa-label').style.color='white';"
                    onmouseout="this.style.background='#dcfce7';this.style.transform='';this.style.boxShadow='';this.querySelector('.qa-icon').style.color='#16a34a';this.querySelector('.qa-label').style.color='#374151';">
                <svg xmlns="http://www.w3.org/2000/svg" class="qa-icon" style="width:22px;height:22px;color:#16a34a;transition:color 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="qa-label" style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;line-height:1.3;transition:color 0.2s;">Lihat Peta</span>
            </a>
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
