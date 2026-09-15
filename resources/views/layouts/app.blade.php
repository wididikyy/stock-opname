<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Stockers') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

<div class="flex min-h-screen">

    {{-- Desktop sidebar (hidden on mobile) --}}
    <aside class="hidden lg:flex lg:flex-col w-60 shrink-0 bg-white border-r border-gray-200">

        <div class="px-6 py-5 border-b border-gray-100">
            <h1 class="text-base font-bold text-amber-800 leading-tight">Stockers</h1>
            <p class="text-xs text-gray-400">Roastery Stock Opname</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('stock.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('stock.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Mutasi Stok
            </a>
            <a href="{{ route('opname.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('opname.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Input Opname
            </a>
            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('laporan.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>
            <a href="{{ route('periode.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('periode.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Tutup Buku
            </a>
        </nav>

        <div class="px-4 py-3 border-t border-gray-100">
            <p class="text-xs text-gray-400 text-center">{{ now()->format('F Y') }}</p>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex flex-1 flex-col min-w-0">

        {{-- Mobile top bar --}}
        <header class="lg:hidden sticky top-0 z-10 flex items-center border-b border-gray-200 bg-white px-4 py-3">
            <span class="font-bold text-amber-800">Stockers</span>
            <span class="ml-auto text-xs text-gray-400">{{ now()->format('M Y') }}</span>
        </header>

        {{-- Page content — extra bottom padding on mobile so bottom nav doesn't cover content --}}
        <main class="flex-1 overflow-y-auto pb-20 lg:pb-0">
            {{ $slot }}
        </main>
    </div>

</div>

{{-- Mobile bottom navigation bar --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-gray-200
            flex items-stretch h-16">

    <a href="{{ route('dashboard') }}"
       class="flex flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium transition-colors
              {{ request()->routeIs('dashboard') ? 'text-amber-700' : 'text-gray-400 hover:text-gray-600' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>

    <a href="{{ route('stock.index') }}"
       class="flex flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium transition-colors
              {{ request()->routeIs('stock.*') ? 'text-amber-700' : 'text-gray-400 hover:text-gray-600' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Mutasi
    </a>

    <a href="{{ route('opname.create') }}"
       class="flex flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium transition-colors
              {{ request()->routeIs('opname.*') ? 'text-amber-700' : 'text-gray-400 hover:text-gray-600' }}">
        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-amber-700 text-white -mt-5 shadow-lg shadow-amber-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </span>
        Input
    </a>

    <a href="{{ route('laporan.index') }}"
       class="flex flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium transition-colors
              {{ request()->routeIs('laporan.*') ? 'text-amber-700' : 'text-gray-400 hover:text-gray-600' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Laporan
    </a>

    <a href="{{ route('periode.index') }}"
       class="flex flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium transition-colors
              {{ request()->routeIs('periode.*') ? 'text-amber-700' : 'text-gray-400 hover:text-gray-600' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Periode
    </a>

</nav>

@livewireScripts
</body>
</html>
