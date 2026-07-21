@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('item-requests.index') }}" class="text-slate-400 hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-100">Ajukan Permintaan Barang</h1>
    </div>

    <x-card>
        <form method="POST" action="{{ route('item-requests.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Produk</label>
                <select name="product_id" required
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                    <option value="">— Pilih Produk —</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->name }} (Stok: {{ $product->stock }} {{ $product->unit }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <x-input label="Jumlah" name="quantity" type="number" :value="old('quantity', 1)" min="1" />

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Catatan (opsional)</label>
                <textarea name="note" rows="3" placeholder="Alasan atau keterangan permintaan..."
                    class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/60">{{ old('note') }}</textarea>
                @error('note') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <i class="fa-solid fa-paper-plane mr-1"></i>Ajukan Permintaan
                </x-button>
                <a href="{{ route('item-requests.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
