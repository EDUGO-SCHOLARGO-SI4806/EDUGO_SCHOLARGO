@props(['user'])

@php
    $classes = match ($user->role) {
        'super_admin' => 'bg-purple-100 text-purple-700',
        'admin' => 'bg-blue-100 text-blue-700',
        'penyelenggara' => 'bg-amber-100 text-amber-700',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp

<span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $user->roleLabel() }}
</span>