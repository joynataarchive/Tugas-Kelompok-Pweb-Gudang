@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold text-slate-100 mb-6">
    {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
</h1>

<x-card>
    <form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <x-input label="SKU" name="sku" :value="old('sku', $product->sku ?? '')" />
        <x-input label="Nama Produk" name="name" :value="old('name', $product->name ?? '')" />

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-1">Deskripsi (opsional)</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-1">Kategori</label>
            <select name="category_id" class="w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-1">Supplier (opsional)</label>
            <select name="supplier_id" class="w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 px-3 py-2">
                <option value="">— Tidak ada —</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-input label="Satuan (pcs/rim/unit/dll)" name="unit" :value="old('unit', $product->unit ?? 'pcs')" />
        <x-input label="Harga Jual (Rp)" name="price" type="number" :value="old('price', $product->price ?? 0)" />
        <x-input label="Harga Modal (Rp, opsional)" name="cost_price" type="number" :value="old('cost_price', $product->cost_price ?? '')" />
        <x-input label="Stok" name="stock" type="number" :value="old('stock', $product->stock ?? 0)" />
        <x-input label="Stok Minimum" name="minimum_stock" type="number" :value="old('minimum_stock', $product->minimum_stock ?? 0)" />

        <div class="flex gap-2 mt-4">
            <x-button variant="primary" type="submit">Simpan</x-button>
            <x-button variant="secondary" type="button" onclick="window.location='{{ route('products.index') }}'">Batal</x-button>
        </div>
    </form>
</x-card>
@endsection
