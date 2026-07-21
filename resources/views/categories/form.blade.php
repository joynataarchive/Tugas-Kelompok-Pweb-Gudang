@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('categories.index') }}" class="text-slate-400 hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-100">
            {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}
        </h1>
    </div>

    <x-card>
        <form method="POST"
              action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            {{-- Nama --}}
            <x-input
                label="Nama Kategori"
                name="name"
                :value="old('name', $category->name ?? '')"
                placeholder="Misal: Elektronik, ATK, Furnitur"
            />

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Deskripsi (opsional)</label>
                <textarea name="description" rows="3"
                    placeholder="Deskripsi singkat kategori ini..."
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                    {{ isset($category) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                </x-button>
                <a href="{{ route('categories.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
