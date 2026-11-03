<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Super Admin') - {{ config('app.name', 'ScholarGo') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased">
        <div class="flex min-h-screen">
            {{-- Sidebar (gaya mockup Dashboard Admin) --}}
            <aside class="hidden w-60 shrink-0 flex-col bg-[#14233c] text-slate-300 md:flex">
                <div class="px-5 py-5">
                    <a href="{{ route('admin.accounts.index') }}" class="flex items-center gap-2 text-lg font-bold text-white">
                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-500 text-sm">S</span>
                        ScholarGO
                    </a>
                    <p class="mt-3 text-[11px] uppercase tracking-wider text-slate-400">Super Admin</p>
                </div>

                <nav class="flex-1 space-y-1 px-3 text-sm">
                    <a href="{{ route('admin.accounts.index') }}"
                       class="flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 font-medium text-white">
                        Manajemen Akun
                    </a>
                </nav>

                @auth
                    <div class="m-3 rounded-lg bg-white/5 px-3 py-3 text-xs">
                        <p class="font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-slate-400">{{ auth()->user()->roleLabel() }}</p>
                    </div>
                @endauth
            </aside>

            <div class="min-w-0 flex-1">
                {{-- Topbar + breadcrumb --}}
                <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-3 text-xs text-slate-500">
                    <nav>
                        <a href="{{ route('admin.accounts.index') }}" class="hover:text-slate-800">Super Admin</a>
                        <span class="mx-1">/</span>
                        @hasSection('breadcrumb')
                            <a href="{{ route('admin.accounts.index') }}" class="hover:text-slate-800">Manajemen Akun</a>
                            <span class="mx-1">/</span>
                            <span class="font-medium text-slate-800">@yield('breadcrumb')</span>
                        @else
                            <span class="font-medium text-slate-800">Manajemen Akun</span>
                        @endif
                    </nav>
                    @auth
                        <span class="flex items-center gap-2">
                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 font-medium text-emerald-700">Super Admin</span>
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-200 font-semibold text-slate-600">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </span>
                    @endauth
                </header>

                <main class="mx-auto max-w-6xl px-6 py-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>