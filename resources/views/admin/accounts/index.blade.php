@extends('layouts.super-admin')

@section('title', 'Daftar Akun')

@section('content')
    <div class="mb-6 flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Daftar Akun</h1>
            <p class="mt-1 text-sm text-gray-500">
                Seluruh akun terdaftar: mahasiswa, admin, dan penyelenggara.
            </p>
        </div>
        <p class="text-sm text-gray-500">Total: <span class="font-medium text-gray-900">{{ $accounts->total() }}</span> akun</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">Status Verifikasi</th>
                    <th class="px-4 py-3">Terdaftar</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($accounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $accounts->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $account->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->roleBadgeClass() }}">{{ $account->roleLabel() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($account->statusBadgeClass())
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->statusBadgeClass() }}">{{ $account->verificationLabel() }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.accounts.show', $account) }}"
                               class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">Belum ada akun terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $accounts->links() }}
    </div>
@endsection