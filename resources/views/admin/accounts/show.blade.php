@extends('layouts.super-admin')

@section('title', $account->name)
@section('breadcrumb', $account->name)

@section('content')
    <a href="{{ route('admin.accounts.index') }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke daftar akun</a>

    @if (session('status'))
        <div class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-800 ring-1 ring-green-200">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">{{ session('error') }}</div>
    @endif

    <div class="mt-4 grid gap-6 lg:grid-cols-3">
        {{-- PBI-04: detail akun --}}
        <section class="rounded-2xl border border-gray-200 bg-white p-6 lg:col-span-1">
            <h1 class="text-xl font-bold text-gray-900">{{ $account->name }}</h1>
            <dl class="mt-4 space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium">{{ $account->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Peran</dt>
                    <dd class="mt-1"><span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->roleBadgeClass() }}">{{ $account->roleLabel() }}</span></dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status Verifikasi</dt>
                    <dd class="mt-1">
                        @if ($account->statusBadgeClass())
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->statusBadgeClass() }}">{{ $account->verificationLabel() }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                @if ($profile)
                    <div>
                        <dt class="text-gray-500">Lembaga</dt>
                        <dd class="font-medium">{{ $profile->institution_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Perwakilan</dt>
                        <dd class="font-medium">{{ $profile->representative_name }}</dd>
                    </div>
                    @if ($profile->submitted_at)
                        <div>
                            <dt class="text-gray-500">Diajukan</dt>
                            <dd class="font-medium">{{ $profile->submitted_at->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                @endif
                <div>
                    <dt class="text-gray-500">Terdaftar</dt>
                    <dd class="font-medium">{{ $account->created_at?->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </section>

        {{-- PBI-06: review dokumen legalitas --}}
        @if ($account->isOrganizer())
            <section class="space-y-6 lg:col-span-2">
                @if (! $profile)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h2 class="text-lg font-bold text-gray-900">Dokumen Legalitas</h2>
                        <p class="mt-3 rounded-xl bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">Penyelenggara ini belum mengirim dokumen legalitas.</p>
                    </div>
                @else
                    @php($complete = $profile->documents->count() >= count($types))

                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h2 class="text-lg font-bold text-gray-900">Dokumen Legalitas</h2>

                        @foreach ($types as $type)
                            @php($document = $profile->documentOfType($type))
                            <div class="mt-3 flex items-center justify-between gap-4 rounded-xl border border-gray-200 px-4 py-3 text-sm">
                                <div class="min-w-0">
                                    <p class="font-medium">{{ $type->label() }}</p>
                                    @if ($document)
                                        <p class="truncate text-gray-500">{{ $document->original_name }} &middot; {{ $document->sizeForHumans() }}</p>
                                    @else
                                        <p class="text-red-600">Belum diunggah</p>
                                    @endif
                                </div>
                                @if ($document)
                                    <a href="{{ route('admin.documents.show', $document) }}" target="_blank" rel="noopener"
                                       class="shrink-0 rounded-xl border border-gray-300 px-3 py-1.5 text-xs font-medium hover:bg-gray-100">Lihat</a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($profile->isRejected())
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-sm">
                            <h3 class="font-semibold text-red-800">Catatan penolakan</h3>
                            <p class="mt-1 text-red-700">{{ $profile->verification_note ?: 'Tidak ada catatan.' }}</p>
                        </div>
                    @endif

                    @if ($profile->reviewed_at)
                        <p class="text-xs text-gray-500">
                            Ditinjau oleh {{ $profile->reviewer?->name ?? 'Super Admin' }} pada {{ $profile->reviewed_at->format('d M Y, H:i') }}.
                        </p>
                    @endif

                    @if ($profile->isPending())
                        <div class="rounded-2xl border border-gray-200 bg-white p-6">
                            <h2 class="text-lg font-bold text-gray-900">Keputusan Verifikasi</h2>

                            @unless ($complete)
                                <p class="mt-3 rounded-xl bg-yellow-50 px-4 py-3 text-sm text-yellow-800 ring-1 ring-yellow-200">Dokumen belum lengkap, akun belum bisa disetujui. Kamu bisa menolak dengan catatan.</p>
                            @endunless

                            <div class="mt-4 grid gap-6 md:grid-cols-2">
                                <form method="POST" action="{{ route('admin.accounts.approve', $account) }}" class="flex flex-col justify-between rounded-xl border border-green-200 bg-green-50 p-4">
                                    @csrf
                                    <p class="text-sm text-green-800">Dokumen sudah sesuai? Setujui akun agar penyelenggara dapat mempublikasikan beasiswa.</p>
                                    <button type="submit" @disabled(! $complete)
                                            class="mt-4 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">Setujui</button>
                                </form>

                                <form method="POST" action="{{ route('admin.accounts.reject', $account) }}" class="rounded-xl border border-red-200 bg-red-50 p-4">
                                    @csrf
                                    <label for="note" class="text-sm font-medium text-red-800">Catatan penolakan (wajib)</label>
                                    <textarea id="note" name="note" rows="3" placeholder="Jelaskan alasan penolakan..."
                                              class="mt-2 w-full rounded-xl border border-red-300 bg-white px-3 py-2 text-sm focus:outline-none">{{ old('note') }}</textarea>
                                    @error('note')
                                        <p class="mt-1 text-xs text-red-700">{{ $message }}</p>
                                    @enderror
                                    <button type="submit" class="mt-3 rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </section>
        @endif
    </div>
@endsection