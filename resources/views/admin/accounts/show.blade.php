@extends('layouts.super-admin')

@section('title', 'Detail Akun')

@section('content')
    <a href="{{ route('admin.accounts.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Kembali ke daftar akun</a>

    <div class="mt-4 max-w-2xl rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-5">
            <h1 class="text-xl font-semibold">{{ $account->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $account->email }}</p>
        </div>

        <dl class="divide-y divide-gray-100 text-sm">
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Nama</dt>
                <dd class="col-span-2 text-gray-900">{{ $account->name }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Email</dt>
                <dd class="col-span-2 text-gray-900">{{ $account->email }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Peran</dt>
                <dd class="col-span-2">
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->roleBadgeClass() }}">{{ $account->roleLabel() }}</span>
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Status verifikasi</dt>
                <dd class="col-span-2">
                    @if ($account->statusBadgeClass())
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->statusBadgeClass() }}">{{ $account->verificationLabel() }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Email terverifikasi</dt>
                <dd class="col-span-2 text-gray-900">
                    {{ $account->email_verified_at?->format('d M Y, H:i') ?? 'Belum' }}
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-gray-500">Terdaftar pada</dt>
                <dd class="col-span-2 text-gray-900">{{ $account->created_at?->format('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>
@endsection