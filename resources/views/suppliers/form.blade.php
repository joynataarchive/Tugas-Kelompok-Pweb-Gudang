@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('suppliers.index') }}" class="text-slate-400 hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-100">
            {{ isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier' }}
        </h1>
    </div>

    <x-card>
        <form method="POST"
              action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
            @csrf
            @if(isset($supplier)) @method('PUT') @endif

            <x-input label="Nama Supplier" name="name" :value="old('name', $supplier->name ?? '')"
                     placeholder="Nama perusahaan supplier" />
            <x-input label="Kontak Person (opsional)" name="contact_person"
                     :value="old('contact_person', $supplier->contact_person ?? '')"
                     placeholder="Nama PIC supplier" />
            <x-input label="Telepon (opsional)" name="phone"
                     :value="old('phone', $supplier->phone ?? '')"
                     placeholder="08xx-xxxx-xxxx" />
            <x-input label="Email (opsional)" name="email" type="email"
                     :value="old('email', $supplier->email ?? '')"
                     placeholder="email@supplier.com" />

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Alamat (opsional)</label>
                <textarea name="address" rows="3"
                    placeholder="Alamat lengkap supplier..."
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">{{ old('address', $supplier->address ?? '') }}</textarea>
                @error('address')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                    {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                </x-button>
                <a href="{{ route('suppliers.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
