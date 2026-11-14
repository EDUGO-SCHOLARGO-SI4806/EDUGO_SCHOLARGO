@extends('layouts.super-admin')

@section('title', 'Manajemen Akun')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Manajemen Akun</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar seluruh akun di ScholarGo.</p>
    </div>

    {{-- PBI-05: cari dan filter --}}
    <form method="GET" action="{{ route('admin.accounts.index') }}"
          class="mb-6 grid gap-3 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-[1fr_180px_200px_auto]">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau email..."
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none">

        <select name="role" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua peran</option>
            @foreach (\App\Models\User::ROLE_LABELS as $value => $label)
                <option value="{{ $value }}" @selected(($filters['role'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua status verifikasi</option>
            @foreach (\App\Models\User::STATUS_LABELS as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Terapkan</button>
            @if ($hasFilter)
                <a href="{{ route('admin.accounts.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">Status Verifikasi</th>
                    <th class="px-4 py-3">Terdaftar</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($accounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $accounts->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $account->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->roleBadgeClass() }}">{{ $account->roleLabel() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($account->statusBadgeClass())
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->statusBadgeClass() }}">{{ $account->verificationLabel() }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.accounts.show', $account) }}"
                               class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            {{ $hasFilter ? 'Tidak ada akun yang cocok dengan pencarian atau filter.' : 'Belum ada akun.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $accounts->links() }}</div>
@endsection