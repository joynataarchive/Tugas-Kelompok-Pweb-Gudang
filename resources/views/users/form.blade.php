@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="text-slate-400 hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-100">
            {{ isset($user) ? 'Edit User' : 'Tambah User' }}
        </h1>
    </div>

    <x-card>
        <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
              x-data="{ role: '{{ old('role', isset($user) ? ($user->roles->first()?->name ?? '') : '') }}' }">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <x-input label="Nama Lengkap" name="name" :value="old('name', isset($user) ? $user->name : '')" placeholder="Nama user" />
            <x-input label="Email" name="email" type="email" :value="old('email', isset($user) ? $user->email : '')" placeholder="email@example.com" />
            <x-input
                label="{{ isset($user) ? 'Password Baru (kosongkan jika tidak diubah)' : 'Password' }}"
                name="password" type="password"
                placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}"
            />

            {{-- Role --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Role</label>
                <select name="role" x-model="role"
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" @selected(old('role', isset($user) ? ($user->roles->first()?->name ?? '') : '') === $r->name)>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
                @error('role') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Supplier ID — tampil hanya jika role = Supplier --}}
            <div class="mb-4" x-show="role === 'Supplier'" x-transition>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Entitas Supplier</label>
                <select name="supplier_id"
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                    <option value="">— Pilih Supplier —</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id', isset($user) ? $user->supplier_id : '') == $s->id)>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                    {{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}
                </x-button>
                <a href="{{ route('users.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
