@props(['user'])

@php
    $classes = match ($user->verification_status) {
        'menunggu' => 'bg-yellow-100 text-yellow-800',
        'disetujui' => 'bg-green-100 text-green-700',
        'ditolak' => 'bg-red-100 text-red-700',
        default => null,
    };
@endphp

@if ($classes)
    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
        {{ $user->verificationLabel() }}
    </span>
@else
    <span class="text-gray-400">-</span>
@endif