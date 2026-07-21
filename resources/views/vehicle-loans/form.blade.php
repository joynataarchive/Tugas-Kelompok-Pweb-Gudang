@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('vehicle-loans.index') }}" class="text-slate-400 hover:text-slate-200 transition"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-100">Pinjam Kendaraan</h1>
    </div>

    @if($vehicles->isEmpty())
        <div class="glass-card text-center py-12">
            <i class="fa-solid fa-car-side text-4xl text-slate-600 mb-3 block"></i>
            <p class="text-slate-400">Tidak ada kendaraan yang tersedia saat ini.</p>
            <a href="{{ route('vehicle-loans.index') }}" class="mt-4 inline-block text-brand-400 text-sm hover:underline">← Kembali</a>
        </div>
    @else
    <x-card>
        <form method="POST" action="{{ route('vehicle-loans.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Kendaraan Tersedia</label>
                <select name="vehicle_id" required
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                    <option value="">— Pilih Kendaraan —</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" @selected(old('vehicle_id') == $v->id)>
                            {{ $v->name }} — {{ $v->plate_number }} ({{ $v->type }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <x-input label="Keperluan / Tujuan" name="purpose" :value="old('purpose')" placeholder="Misal: Antar barang ke Jakarta" />
            <x-input label="Tanggal & Waktu Pinjam" name="borrowed_at" type="datetime-local"
                :value="old('borrowed_at', now()->format('Y-m-d\TH:i'))" />

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Catatan (opsional)</label>
                <textarea name="notes" rows="2" placeholder="Informasi tambahan..."
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-car mr-1"></i>Pinjam Kendaraan
                </x-button>
                <a href="{{ route('vehicle-loans.index') }}"><x-button variant="secondary" type="button">Batal</x-button></a>
            </div>
        </form>
    </x-card>
    @endif
</div>
@endsection
