@extends('layouts.app')

@section('content')
<div class="max-w-lg space-y-6">
    <h1 class="text-2xl font-bold text-slate-100">Ubah Akun</h1>

    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="rounded-xl {{ $type==='success'?'bg-emerald-500/15 border-emerald-500/30 text-emerald-300':'bg-red-500/15 border-red-500/30 text-red-300' }} border px-4 py-3 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- Info Profil --}}
    <x-card title="Informasi Profil">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-1">
            @csrf @method('PUT')
            <x-input label="Nama" name="name" :value="old('name', $user->name)" />
            <x-input label="Email" name="email" type="email" :value="old('email', $user->email)" />
            <div class="mt-4">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>Simpan Profil
                </x-button>
            </div>
        </form>
    </x-card>

    {{-- Ganti Password --}}
    <x-card title="Ganti Password">
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-1">
            @csrf @method('PUT')
            <x-input label="Password Saat Ini" name="current_password" type="password" />
            <x-input label="Password Baru" name="password" type="password" placeholder="Min. 8 karakter" />
            <x-input label="Konfirmasi Password Baru" name="password_confirmation" type="password" />
            <div class="mt-4">
                <x-button variant="secondary" type="submit">
                    <i class="fa-solid fa-lock mr-1"></i>Ubah Password
                </x-button>
            </div>
        </form>
    </x-card>

    {{-- Info Role --}}
    <x-card title="Role Akun">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-500/20 text-brand-400">
                <i class="fa-solid fa-user-shield text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-100">{{ $user->name }}</p>
                <div class="flex gap-2 mt-1">
                    @foreach($user->roles as $role)
                        <span class="rounded-full bg-brand-500/15 border border-brand-500/20 px-2 py-0.5 text-xs font-medium text-brand-300">{{ $role->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection
