<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Admin Panel — Kelola data dengan mudah dan efisien')">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'AdminPanel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ==========================================
           RESET & BASE
        ========================================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #F1F7D4;
            color: #374151;
            -webkit-font-smoothing: antialiased;
        }

        /* ==========================================
           LAYOUT SHELL
        ========================================== */
        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ==========================================
           SIDEBAR
        ========================================== */
        #sidebar {
            width: 260px;
            flex-shrink: 0;
            background-color: #1F6F5F;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            transform: translateX(0); /* VISIBLE by default on desktop */
        }

        /* Mobile: hide sidebar by default */
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
        }

        /* ==========================================
           MAIN CONTENT
        ========================================== */
        #main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: 260px; /* Push right on desktop */
            transition: margin-left 0.3s ease;
        }

        /* Mobile: no margin */
        @media (max-width: 1023px) {
            #main-content {
                margin-left: 0;
            }
        }

        /* ==========================================
           SIDEBAR PARTS
        ========================================== */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            flex-shrink: 0;
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #1591DC;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(21,145,220,0.4);
        }
        .sidebar-logo-text {
            font-size: 16px; font-weight: 700;
            color: #fff; letter-spacing: -0.02em;
            white-space: nowrap;
        }
        .sidebar-logo-sub {
            font-size: 11px; color: rgba(255,255,255,0.65);
            margin-top: 2px;
        }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .nav-section-label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.55);
            padding: 0 10px;
            margin: 16px 0 6px;
        }
        .nav-section-label:first-child { margin-top: 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px;
            border-radius: 10px;
            font-size: 13.5px; font-weight: 500;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            margin-bottom: 2px;
            transition: background 0.18s, color 0.18s;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.8; }
        .nav-item:hover { background: rgba(255,255,255,0.18); color: #fff; }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active {
            background: rgba(255,255,255,0.22);
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .nav-item.active svg { opacity: 1; }

        .nav-badge {
            margin-left: auto;
            background: #1591DC;
            color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 1px 7px;
            border-radius: 999px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,0.18);
            flex-shrink: 0;
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.18s;
        }
        .sidebar-user:hover { background: rgba(255,255,255,0.12); }
        .sidebar-avatar {
            width: 32px; height: 32px; border-radius: 8px;
            background: #1591DC;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700;
            flex-shrink: 0;
        }

        /* ==========================================
           TOPBAR
        ========================================== */
        .topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid rgba(159,203,173,0.25);
            display: flex; align-items: center;
            padding: 0 20px; gap: 12px;
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            flex-shrink: 0;
        }

        .hamburger-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: none; /* Hidden on desktop */
            align-items: center; justify-content: center;
            border: none; background: transparent; cursor: pointer;
            color: #6b7280;
            flex-shrink: 0;
            transition: background 0.18s;
        }
        .hamburger-btn:hover { background: #F1F7D4; color: #3f7a58; }

        @media (max-width: 1023px) {
            .hamburger-btn { display: flex; }
        }

        .topbar-search {
            display: flex; align-items: center; gap: 8px;
            background: #F1F7D4;
            border: 1.5px solid rgba(159,203,173,0.45);
            border-radius: 10px;
            padding: 7px 14px;
            flex: 1; max-width: 380px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .topbar-search:focus-within {
            border-color: #1591DC;
            box-shadow: 0 0 0 3px rgba(21,145,220,0.12);
        }
        .topbar-search input {
            background: transparent; border: none; outline: none;
            font-size: 13.5px; color: #4b5563; width: 100%;
            font-family: inherit;
        }
        .topbar-search input::placeholder { color: #9ca3af; }

        /* Hide search on mobile */
        @media (max-width: 767px) {
            .topbar-search { display: none !important; }
        }

        .topbar-icon-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; cursor: pointer;
            color: #6b7280; position: relative;
            transition: background 0.18s, color 0.18s;
            flex-shrink: 0;
        }
        .topbar-icon-btn:hover { background: #F1F7D4; color: #3f7a58; }

        .notif-dot {
            position: absolute; top: -1px; right: -1px;
            width: 10px; height: 10px;
            background: #1591DC; border-radius: 50%;
            border: 2px solid #fff;
        }

        .topbar-user-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px;
            border-radius: 10px;
            border: none; background: transparent; cursor: pointer;
            transition: background 0.18s;
        }
        .topbar-user-btn:hover { background: #F1F7D4; }

        .topbar-avatar {
            width: 32px; height: 32px; border-radius: 9px;
            background: #1F6F5F;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700;
        }

        .topbar-user-info { text-align: left; }
        .topbar-user-name { font-size: 12px; font-weight: 600; color: #1f2937; line-height: 1; white-space: nowrap; }
        .topbar-user-role { font-size: 10px; color: #9ca3af; margin-top: 2px; }

        /* Hide user info text on mobile */
        @media (max-width: 639px) {
            .topbar-user-info { display: none; }
        }

        .mobile-page-title {
            font-size: 15px; font-weight: 600; color: #1f2937;
            flex: 1;
        }
        @media (min-width: 1024px) {
            .mobile-page-title { display: none; }
        }

        .desktop-breadcrumb {
            display: none;
            flex: 1;
        }
        @media (min-width: 1024px) {
            .desktop-breadcrumb {
                display: flex;
                align-items: center; gap: 6px;
                font-size: 13px; color: #9ca3af;
            }
        }

        /* ==========================================
           DROPDOWN
        ========================================== */
        .dropdown-wrap { position: relative; }
        .dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
            border: 1px solid #f3f4f6;
            overflow: hidden; z-index: 999;
            min-width: 200px;
            animation: fadeInDrop 0.18s ease-out;
        }
        @keyframes fadeInDrop {
            from { opacity:0; transform: translateY(-6px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px;
            font-size: 13.5px; color: #4b5563;
            text-decoration: none;
            background: transparent; border: none;
            width: 100%; text-align: left;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s;
        }
        .dropdown-item:hover { background: #F1F7D4; color: #3f7a58; }
        .dropdown-item.danger { color: #ef4444; }
        .dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }

        /* ==========================================
           OVERLAY BACKDROP
        ========================================== */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(3px);
            z-index: 99;
        }
        #sidebar-overlay.show { display: block; }

        /* ==========================================
           MAIN PAGE AREA
        ========================================== */
        .page-content {
            flex: 1;
            padding: 24px;
        }
        @media (max-width: 767px) {
            .page-content { padding: 16px; }
        }

        .page-footer {
            padding: 14px 24px;
            border-top: 1px solid rgba(159,203,173,0.25);
            background: rgba(255,255,255,0.6);
            font-size: 12px; color: #9ca3af;
            display: flex; flex-wrap: wrap; align-items: center;
            justify-content: space-between; gap: 8px;
        }

        /* ==========================================
           STAT CARDS
        ========================================== */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(159,203,173,0.18), 0 1px 3px rgba(0,0,0,0.06);
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(159,203,173,0.3), 0 2px 8px rgba(0,0,0,0.07);
            transform: translateY(-2px);
        }

        /* ==========================================
           CONTENT CARD
        ========================================== */
        .content-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(159,203,173,0.18), 0 1px 3px rgba(0,0,0,0.06);
        }
        .content-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        .content-card-title { font-size: 15px; font-weight: 600; color: #1f2937; }

        /* ==========================================
           TABLE
        ========================================== */
        .data-table { width: 100%; font-size: 13.5px; text-align: left; border-collapse: collapse; }
        .data-table thead th {
            padding: 10px 20px;
            font-size: 11px; font-weight: 600;
            color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em;
            background: rgba(241,247,212,0.55);
            border-bottom: 1px solid #f3f4f6;
            white-space: nowrap;
        }
        .data-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.15s; }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(241,247,212,0.4); }
        .data-table tbody td { padding: 13px 20px; color: #4b5563; }

        /* ==========================================
           BADGES
        ========================================== */
        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 9px; border-radius: 999px;
            font-size: 11px; font-weight: 600;
        }
        .badge-success { background: rgba(159,203,173,0.25); color: #2a4e3a; }
        .badge-warning  { background: #fef9c3; color: #a16207; }
        .badge-danger   { background: #fef2f2; color: #dc2626; }
        .badge-info     { background: #e8f4fd; color: #0b63a0; }
        .badge-gray     { background: #f3f4f6; color: #4b5563; }

        /* ==========================================
           BUTTONS
        ========================================== */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 10px;
            font-size: 13.5px; font-weight: 600;
            cursor: pointer; border: none;
            text-decoration: none;
            font-family: inherit;
            transition: all 0.2s;
        }
        .btn-accent { background: #1591DC; color: #fff; }
        .btn-accent:hover { background: #0e7bc4; box-shadow: 0 4px 12px rgba(21,145,220,0.3); transform: translateY(-1px); }
        .btn-primary { background: #1F6F5F; color: #fff; }
        .btn-primary:hover { background: #76b38e; box-shadow: 0 4px 12px rgba(159,203,173,0.4); transform: translateY(-1px); }
        .btn-ghost { background: transparent; color: #4b5563; border: 1.5px solid #e5e7eb; }
        .btn-ghost:hover { background: #F1F7D4; color: #3f7a58; border-color: #1F6F5F; }

        /* ==========================================
           PROGRESS BAR
        ========================================== */
        .progress-track {
            width: 100%; height: 7px;
            background: rgba(241,247,212,0.8);
            border-radius: 999px; overflow: hidden;
        }
        .progress-fill { height: 100%; border-radius: 999px; transition: width 0.7s ease-out; }

        /* ==========================================
           GRID UTILITIES
        ========================================== */
        .grid-3col {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        @media (max-width: 1023px) { .grid-3col { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 639px)  { .grid-3col { grid-template-columns: 1fr; } }

        .grid-4col {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        @media (max-width: 1279px) { .grid-4col { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 639px)  { .grid-4col { grid-template-columns: 1fr; } }

        .grid-3col-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }
        @media (max-width: 1023px) { .grid-3col-main { grid-template-columns: 1fr; } }

        .grid-3col-bottom {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }
        @media (max-width: 1279px) { .grid-3col-bottom { grid-template-columns: 1fr; } }

        .grid-4actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        @media (max-width: 639px) { .grid-4actions { grid-template-columns: repeat(2, 1fr); } }

        .mb-16 { margin-bottom: 16px; }
        .mb-6  { margin-bottom: 24px; }
        .gap-6 { gap: 24px; }
        .hidden { display: none !important; }
        .flex   { display: flex; }
        .divide-y > * + * { border-top: 1px solid #f9fafb; }

        /* ==========================================
           PAGINATION STYLES
        ========================================== */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none !important;
            gap: 6px;
            align-items: center;
            margin: 0;
            flex-wrap: wrap;
        }
        .page-item {
            list-style: none !important;
            display: inline-flex;
            margin: 0;
            padding: 0;
        }
        .page-item .page-link,
        .pagination a,
        .pagination span {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 600;
            color: #1F6F5F;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.2s ease;
            line-height: 1;
        }
        .page-item .page-link:hover,
        .pagination a:hover {
            background-color: #F1F7D4;
            border-color: #1F6F5F;
            color: #1F6F5F;
        }
        .page-item.active .page-link,
        .pagination .active span {
            z-index: 3;
            color: #fff !important;
            background-color: #1F6F5F !important;
            border-color: #1F6F5F !important;
        }
        .page-item.disabled .page-link,
        .pagination .disabled span {
            color: #9ca3af !important;
            pointer-events: none;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
        }
        /* Handle SVG inside pagination */
        nav[role="navigation"] svg,
        .pagination svg,
        .page-link svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            flex-wrap: wrap;
            gap: 12px;
        }
    </style>

    @stack('head')
</head>
<body>

    {{-- OVERLAY BACKDROP (mobile) --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="admin-shell">

        {{-- =================== SIDEBAR =================== --}}
        <aside id="sidebar">

            {{-- Logo --}}
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="sidebar-logo-text">{{ config('app.name', 'AdminPanel') }}</div>
                    <div class="sidebar-logo-sub">Management System</div>
                </div>
                <button onclick="closeSidebar()" class="sidebar-close-btn"
                        style="display:none;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.75);padding:4px;border-radius:6px;transition:background 0.18s;"
                        id="sidebar-close-btn"
                        onmouseover="this.style.background='rgba(255,255,255,0.12)'"
                        onmouseout="this.style.background='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            @php
                $pkjSelesai = \App\Models\PekerjaanSda::where('progress', 100)->count();
                $pkjBelum = \App\Models\PekerjaanSda::where('progress', '<', 100)->count();
                
                $suratSelesai = \App\Models\SuratPermohonan::where('status', 'Diterima')->orWhere('status', 'Diproses')->count();
                $suratMenunggu = \App\Models\SuratPermohonan::where('status', 'Menunggu')->count();
                
                $userRole = auth()->user()->role ?? '';
                $isKecamatan = ($userRole === 'Kecamatan');
            @endphp
            <nav class="sidebar-nav">

                @if(!$isKecamatan)
                <p class="nav-section-label">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                @endif

                <p class="nav-section-label">Manajemen Aspirasi & Pekerjaan</p>

                <a href="{{ route('admin.survei-reses.index') }}" class="nav-item {{ request()->routeIs('admin.survei-reses.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    <span>Survei Reses</span>
                </a>

                <a href="{{ route('admin.surat-permohonan.index') }}" class="nav-item {{ request()->routeIs('admin.surat-permohonan.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                        <span>Surat & Usulan</span>
                        <div style="display:flex; gap:4px;">
                            @if($suratMenunggu > 0)<span class="nav-badge" style="background:#d97706; min-width:18px;" title="Menunggu">{{ $suratMenunggu }}</span>@endif
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.pekerjaan-sda.index') }}" class="nav-item {{ request()->routeIs('admin.pekerjaan-sda.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                    </svg>
                    <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                        <span>Pekerjaan SDA</span>
                        <div style="display:flex; gap:4px;">
                            @if($pkjSelesai > 0)<span class="nav-badge" style="background:#059669; min-width:18px;" title="Progress 100%">{{ $pkjSelesai }}</span>@endif
                            @if($pkjBelum > 0)<span class="nav-badge" style="background:#d97706; min-width:18px;" title="Belum 100%">{{ $pkjBelum }}</span>@endif
                        </div>
                    </div>
                </a>

              

                <a href="{{ route('admin.pekerjaan-sda.map') }}" class="nav-item {{ request()->routeIs('admin.pekerjaan-sda.map') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>Peta Pekerjaan SDA</span>
                </a>

                @if(!$isKecamatan)
                <p class="nav-section-label">Master Data</p>

                <a href="{{ route('admin.master.dewan') }}" class="nav-item {{ request()->routeIs('admin.master.dewan') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span>Data Dewan</span>
                </a>

                <a href="{{ route('admin.fraksi.index') }}" class="nav-item {{ request()->routeIs('admin.fraksi.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
                    </svg>
                    <span>Data Fraksi</span>
                </a>

                <a href="{{ route('admin.pelaksana.index') }}" class="nav-item {{ request()->routeIs('admin.pelaksana.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span>Data Pelaksana</span>
                </a>

                <a href="{{ route('admin.vendor.index') }}" class="nav-item {{ request()->routeIs('admin.vendor.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                    </svg>
                    <span>Data Vendor</span>
                </a>

                <a href="{{ route('admin.master.wilayah') }}" class="nav-item {{ request()->routeIs('admin.master.wilayah') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.247s-4.135-5.321-4.135-9.253a7.135 7.135 0 1114.27 0c0 3.932-4.135 9.253-4.135 9.253z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    <span>Data Wilayah</span>
                </a>

                <p class="nav-section-label">Laporan & Pengaturan</p>

                <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <span>Laporan</span>
                </a>

                <p class="nav-section-label">Sistem</p>

                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Manajemen Akses</span>
                </a>
                @endif
                
                @if(in_array(strtolower(auth()->user()->role ?? ''), ['super admin', 'admin']))
                <a href="{{ route('admin.activity-log.index') }}" class="nav-item {{ request()->routeIs('admin.activity-log.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375c0 .621-.504 1.125-1.125 1.125H6.375c-.621 0-1.125-.504-1.125-1.125v-9.375c0-.621.504-1.125 1.125-1.125z" />
                    </svg>
                    <span>Activity Log</span>
                </a>
                @endif
                
                @if(in_array(strtolower(auth()->user()->role ?? ''), ['super admin', 'admin', 'sudin']))
                <a href="{{ route('admin.backup-database') }}" class="nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 17.25V19.5a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 19.5v-2.25M19.5 12l-7.5 7.5-7.5-7.5m7.5-9v16.5" />
                    </svg>
                    <span>Backup Database</span>
                </a>
                @endif

            </nav>

            {{-- Sidebar Footer --}}
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="color:#fff;font-size:13px;font-weight:600;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </p>
                        <p style="color:rgba(255,255,255,0.6);font-size:11px;margin:2px 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ auth()->user()->email ?? 'admin@example.com' }}
                        </p>
                    </div>
                    @if(Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar"
                                style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.55);padding:4px;border-radius:6px;transition:color 0.18s;"
                                onmouseover="this.style.color='white'"
                                onmouseout="this.style.color='rgba(255,255,255,0.55)'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </aside>

        {{-- =================== MAIN CONTENT =================== --}}
        <div id="main-content">

            {{-- TOPBAR --}}
            <header class="topbar">

                {{-- Hamburger (mobile only) --}}
                <button class="hamburger-btn" onclick="openSidebar()">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Mobile Title --}}
                <span class="mobile-page-title">@yield('page_title', 'Dashboard')</span>

                {{-- Desktop Breadcrumb --}}
                <nav class="desktop-breadcrumb">
                    <span style="color:#9ca3af;">AdminPanel</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:#d1d5db;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span style="color:#374151;font-weight:500;">@yield('page_title', 'Dashboard')</span>
                </nav>

                {{-- Search --}}
                <form class="topbar-search" method="GET" action="{{ url()->current() }}">
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#9ca3af;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                    </button>
                    <input type="text" name="search" placeholder="Ketik lalu tekan Enter..." id="global-search" value="{{ request('search') }}">
                </form>

                {{-- Right Actions --}}
                <div style="display:flex;align-items:center;gap:8px;margin-left:auto;flex-shrink:0;">

                    {{-- Notification --}}
                    <div class="dropdown-wrap">
                        <button class="topbar-icon-btn" id="notif-btn" onclick="toggleDropdown('notif-dropdown', event)">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                            <span class="notif-dot"></span>
                        </button>
                        <div id="notif-dropdown" class="dropdown-menu hidden" style="width:300px;">
                            <div class="dropdown-header" style="display:flex;align-items:center;justify-content:space-between;">
                                <span style="font-size:14px;font-weight:600;color:#1f2937;">Notifikasi</span>
                                <span class="badge badge-info">3 Baru</span>
                            </div>
                            @php
                            $notifItems = [
                                ['bg'=>'#e8f4fd','ic'=>'#1591DC','title'=>'Pengguna baru mendaftar','time'=>'2 menit lalu'],
                                ['bg'=>'#dceee2','ic'=>'#3f7a58','title'=>'Stok produk hampir habis','time'=>'1 jam lalu'],
                                ['bg'=>'#fef9c3','ic'=>'#a16207','title'=>'Laporan bulanan siap','time'=>'3 jam lalu'],
                            ];
                            @endphp
                            <div class="divide-y">
                                @foreach($notifItems as $n)
                                <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;cursor:pointer;transition:background 0.15s;"
                                     onmouseover="this.style.background='rgba(241,247,212,0.5)'" onmouseout="this.style.background=''">
                                    <div style="width:30px;height:30px;border-radius:8px;background:{{ $n['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:{{ $n['ic'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:500;color:#374151;margin:0;">{{ $n['title'] }}</p>
                                        <p style="font-size:11px;color:#9ca3af;margin:3px 0 0;">{{ $n['time'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div style="padding:10px 16px;text-align:center;border-top:1px solid #f3f4f6;">
                                <a href="#" style="font-size:12px;font-weight:600;color:#1591DC;text-decoration:none;">Lihat semua</a>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="width:1px;height:22px;background:#e5e7eb;"></div>

                    {{-- User --}}
                    <div class="dropdown-wrap">
                        <button class="topbar-user-btn" onclick="toggleDropdown('user-dropdown', event)">
                            <div class="topbar-avatar">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="topbar-user-info">
                                <div class="topbar-user-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                                <div class="topbar-user-role">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:#9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="user-dropdown" class="dropdown-menu hidden">
                            <div class="dropdown-header">
                                <p style="font-size:14px;font-weight:600;color:#1f2937;margin:0;">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                            </div>
                            <a href="#" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <a href="#" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Pengaturan
                            </a>
                            <div style="border-top:1px solid #f3f4f6;margin-top:4px;">
                                @if(Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="page-content">
                @if(session('success'))
                <div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;background:#dceee2;color:#2a4e3a;padding:12px 16px;border-radius:12px;border:1px solid #bcddc8;font-size:13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;background:#fef2f2;color:#dc2626;padding:12px 16px;border-radius:12px;border:1px solid #fecaca;font-size:13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
                @endif
                @yield('content')
            </main>

            {{-- FOOTER --}}
            <footer class="page-footer">
                <span>© {{ date('Y') }} {{ config('app.name', 'AdminPanel') }}. Semua hak cipta dilindungi.</span>
                <span>Dibuat dengan ❤️ Sudin SDA &amp; Tata Usaha</span>
            </footer>

        </div>{{-- /main-content --}}

    </div>{{-- /admin-shell --}}

    <script>
        // ---- Sidebar Mobile Toggle ----
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('sidebar-close-btn');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
            if (closeBtn) closeBtn.style.display = 'flex';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
            if (closeBtn) closeBtn.style.display = 'none';
        }

        // ---- Dropdowns ----
        function toggleDropdown(id, event) {
            event.stopPropagation();
            const target = document.getElementById(id);
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            allDropdowns.forEach(dd => { if (dd.id !== id) dd.classList.add('hidden'); });
            target.classList.toggle('hidden');
        }

        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(dd => dd.classList.add('hidden'));
        });

        // ---- Show close btn when mobile ----
        function checkMobile() {
            if (window.innerWidth < 1024) {
                if (closeBtn) closeBtn.style.display = 'flex';
            } else {
                if (closeBtn) closeBtn.style.display = 'none';
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
        checkMobile();
        window.addEventListener('resize', checkMobile);

        // ---- Global Search for Tables ----
        // (Live filter removed to avoid confusion with pagination. Form will submit on Enter).
    </script>

    @stack('scripts')
</body>
</html>
