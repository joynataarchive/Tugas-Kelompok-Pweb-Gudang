@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-slate-100">Daftar Produk</h1>
    <x-button variant="primary" onclick="window.location='{{ route('products.create') }}'">
        <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
    </x-button>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-4 py-2">
        {{ session('success') }}
    </div>
@endif

<x-card>
    <table class="w-full text-left text-sm text-slate-300">
        <thead>
            <tr class="border-b border-white/10 text-slate-400">
                <th class="py-2">SKU</th>
                <th class="py-2">Nama</th>
                <th class="py-2">Kategori</th>
                <th class="py-2">Supplier</th>
                <th class="py-2">Stok</th>
                <th class="py-2">Harga</th>
                <th class="py-2">Status</th>
                <th class="py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-b border-white/5">
                    <td class="py-2">{{ $product->sku }}</td>
                    <td class="py-2 text-slate-100">{{ $product->name }}</td>
                    <td class="py-2">{{ $product->category->name }}</td>
                    <td class="py-2">{{ $product->supplier->name ?? '-' }}</td>
                    <td class="py-2">{{ $product->stock }} {{ $product->unit }}</td>
                    <td class="py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-2">
                        @if($product->isLowStock())
                            <x-badge status="low-stock">Stok Rendah</x-badge>
                        @else
                            <x-badge status="in-stock">Aman</x-badge>
                        @endif
                    </td>
                    <td class="py-2 text-right space-x-2">
                        <a href="{{ route('products.edit', $product) }}" class="text-brand-400 hover:underline">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-card>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
