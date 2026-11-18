<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Super Admin') - ScholarGO</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="flex min-h-screen bg-gray-50 text-gray-800 antialiased">

        {{-- Sidebar (gaya sama dengan Portal Mitra: navy + hijau brand) --}}
        <aside class="hidden w-64 shrink-0 flex-col justify-between bg-[#0c1524] text-gray-300 md:flex">
            <div>
                <div class="flex items-center gap-3 border-b border-[#111c2e] px-6 py-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white">Scholar<span class="text-emerald-500">GO</span></h1>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Super Admin</p>
                    </div>
                </div>

                <nav class="mt-4 space-y-1.5 px-3 text-sm">
                    <a href="{{ route('admin.accounts.index') }}"
                       class="flex items-center gap-3 rounded-xl bg-emerald-600 px-3.5 py-2.5 font-medium text-white shadow-md shadow-emerald-600/30 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span>Manajemen Akun</span>
                    </a>
                </nav>
            </div>

            @auth
                <div class="flex items-center justify-between border-t border-[#111c2e] p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-700 text-xs font-bold uppercase text-white">
                            {{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 2)->upper() }}
                        </div>
                        <div class="text-xs">
                            <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ auth()->user()->roleLabel() }}</p>
                        </div>
                    </div>
                    @if (\Illuminate\Support\Facades\Route::has('logout'))
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-[#111c2e] hover:text-rose-400" title="Keluar / Logout">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a2 2 0 01-2-2V7a2 2 0 012-2h4a3 3 0 013 3v1" /></svg>
                            </button>
                        </form>
                    @endif
                </div>
            @endauth
        </aside>

        {{-- Konten --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4">
                <nav class="flex items-center gap-3 text-sm text-gray-500">
                    <a href="{{ route('admin.accounts.index') }}" class="font-medium hover:text-emerald-600">Super Admin</a>
                    <span>/</span>
                    @hasSection('breadcrumb')
                        <a href="{{ route('admin.accounts.index') }}" class="font-medium hover:text-emerald-600">Manajemen Akun</a>
                        <span>/</span>
                        <span class="font-semibold text-gray-900">@yield('breadcrumb')</span>
                    @else
                        <span class="font-semibold text-gray-900">Manajemen Akun</span>
                    @endif
                </nav>

                <div class="hidden items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 sm:flex">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Periode Aktif 2026</span>
                </div>
            </header>

            <main class="flex-1 bg-gray-50/80 p-6 md:p-8">
                <div class="mx-auto max-w-6xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>