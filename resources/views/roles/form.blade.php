@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('roles.index') }}" class="text-slate-400 hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-100">
            {{ isset($role) ? 'Edit Role' : 'Tambah Role' }}
        </h1>
    </div>

    <x-card>
        <form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
            @csrf
            @if(isset($role)) @method('PUT') @endif

            <x-input label="Nama Role" name="name" :value="old('name', $role->name ?? '')"
                     placeholder="Contoh: Manajer, Kasir" />

            @if(isset($permissions) && $permissions->count())
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Permissions</label>
                <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto pr-1">
                    @foreach($permissions as $perm)
                        <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                class="rounded border-white/20 bg-slate-800 text-brand-500 focus:ring-brand-500"
                                @checked(in_array($perm->name, old('permissions', $assignedPerms ?? [])))>
                            {{ $perm->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                    {{ isset($role) ? 'Simpan Perubahan' : 'Buat Role' }}
                </x-button>
                <a href="{{ route('roles.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
