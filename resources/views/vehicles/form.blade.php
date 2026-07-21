@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('vehicles.index') }}" class="text-slate-400 hover:text-slate-200 transition"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-100">{{ isset($vehicle) ? 'Edit Kendaraan' : 'Tambah Kendaraan' }}</h1>
    </div>
    <x-card>
        <form method="POST" action="{{ isset($vehicle) ? route('vehicles.update', $vehicle) : route('vehicles.store') }}">
            @csrf
            @if(isset($vehicle)) @method('PUT') @endif

            <x-input label="Nama Kendaraan" name="name" :value="old('name', $vehicle->name ?? '')" placeholder="Misal: Avanza Putih" />
            <x-input label="Plat Nomor" name="plate_number" :value="old('plate_number', $vehicle->plate_number ?? '')" placeholder="B 1234 XYZ" />
            <x-input label="Tipe" name="type" :value="old('type', $vehicle->type ?? 'Mobil')" placeholder="Mobil, Motor, Truk, dll" />

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Status</label>
                <select name="status" class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                    @foreach(['available' => 'Tersedia', 'borrowed' => 'Dipinjam', 'maintenance' => 'Perbaikan'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $vehicle->status ?? 'available') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Catatan (opsional)</label>
                <textarea name="notes" rows="3" placeholder="Kondisi kendaraan, informasi tambahan..."
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">{{ old('notes', $vehicle->notes ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i>{{ isset($vehicle) ? 'Simpan Perubahan' : 'Tambah Kendaraan' }}
                </x-button>
                <a href="{{ route('vehicles.index') }}"><x-button variant="secondary" type="button">Batal</x-button></a>
            </div>
        </form>
    </x-card>
</div>
@endsection
