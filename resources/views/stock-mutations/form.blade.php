@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold text-slate-100 mb-6">Catat Mutasi Stok</h1>

<x-card>
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-500/20 text-red-300 border border-red-500/30 px-4 py-2">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('stock-mutations.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-1">Produk</label>
            <select name="product_id" class="w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (stok: {{ $product->stock }} {{ $product->unit }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-1">Tipe Mutasi</label>
            <select name="type" class="w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2">
                <option value="in">Masuk</option>
                <option value="out">Keluar</option>
            </select>
        </div>

        <x-input label="Jumlah" name="quantity" type="number" />
        <x-input label="Catatan (opsional)" name="note" />

        <div class="flex gap-2 mt-4">
            <x-button variant="primary" type="submit">Simpan</x-button>
            <x-button variant="secondary" type="button" onclick="window.location='{{ route('stock-mutations.index') }}'">Batal</x-button>
        </div>
    </form>
</x-card>
@endsection
