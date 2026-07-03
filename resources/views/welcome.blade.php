<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AdminPanel') }} — Selamat Datang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full min-h-screen bg-secondary-100 flex items-center justify-center p-4">

    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-DEFAULT/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-accent-DEFAULT/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-primary-200/30 rounded-full blur-2xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-primary-200/30 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-br from-primary-DEFAULT to-primary-400 px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-0 right-8 w-8 h-8 bg-accent-DEFAULT/40 rounded-full"></div>

                <div class="relative">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <div class="w-10 h-10 bg-accent-DEFAULT rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-white text-2xl font-bold">{{ config('app.name', 'AdminPanel') }}</h1>
                    <p class="text-primary-100/80 text-sm mt-1">Sistem Manajemen Terpadu</p>
                </div>
            </div>

            <!-- Body -->
            <div class="px-8 py-8">
                <h2 class="text-xl font-bold text-gray-800 mb-1">Selamat Datang! 👋</h2>
                <p class="text-gray-500 text-sm mb-6">Masuk ke akun Anda untuk mengakses dashboard admin.</p>

                @if (Route::has('login'))
                <div class="space-y-3">
                    @auth
                        <a href="{{ url('/admin/dashboard') }}"
                           class="btn btn-secondary w-full justify-center py-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                            </svg>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn btn-primary w-full justify-center py-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk ke Sistem
                        </a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="btn btn-ghost w-full justify-center py-3 text-sm">
                            Daftar Akun Baru
                        </a>
                        @endif
                    @endauth
                </div>
                @endif

                <!-- Features highlight -->
                <div class="mt-8 grid grid-cols-3 gap-3">
                    @php
                    $features = [
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Analitik'],
                        ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label' => 'Aman'],
                        ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'label' => 'Responsif'],
                    ];
                    @endphp
                    @foreach($features as $f)
                    <div class="text-center p-3 bg-secondary-100 rounded-xl">
                        <div class="w-8 h-8 bg-primary-DEFAULT/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-600">{{ $f['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} {{ config('app.name', 'AdminPanel') }}. Dibuat dengan Tailwind CSS.
        </p>
    </div>

</body>
</html>
