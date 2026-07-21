@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Daftar Produk</h1>
        @hasanyrole('Super Admin|Staff Gudang')
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
        @endhasanyrole
    </div>

    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="rounded-xl {{ $type==='success'?'bg-emerald-500/15 border-emerald-500/30 text-emerald-300':'bg-red-500/15 border-red-500/30 text-red-300' }} border px-4 py-3 text-sm">
                <i class="fa-solid fa-{{ $type==='success'?'circle-check':'circle-exclamation' }} mr-2"></i>{{ session($type) }}
            </div>
        @endif
    @endforeach

    <x-card>
        {{-- Search --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, SKU, atau kategori..."
                class="flex-1 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">#</th>
                        <th class="py-2 pr-4">SKU</th>
                        <th class="py-2 pr-4">Nama</th>
                        <th class="py-2 pr-4">Kategori</th>
                        <th class="py-2 pr-4">Supplier</th>
                        <th class="py-2 pr-4">Stok</th>
                        <th class="py-2 pr-4">Harga</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($products as $i => $product)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-500">{{ $products->firstItem() + $i }}</td>
                            <td class="py-2.5 pr-4 font-mono text-xs text-slate-400">{{ $product->sku }}</td>
                            <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $product->name }}</td>
                            <td class="py-2.5 pr-4">{{ $product->category->name ?? '-' }}</td>
                            <td class="py-2.5 pr-4 text-slate-400">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="py-2.5 pr-4">{{ $product->stock }} {{ $product->unit }}</td>
                            <td class="py-2.5 pr-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="py-2.5 pr-4">
                                @if($product->isLowStock())
                                    <x-badge status="low-stock">Stok Rendah</x-badge>
                                @else
                                    <x-badge status="in-stock">Aman</x-badge>
                                @endif
                            </td>
                            <td class="py-2.5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    {{-- Tambah ke Keranjang --}}
                                    <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg bg-brand-600/20 border border-brand-500/30 px-2 py-1 text-xs font-medium text-brand-300 hover:bg-brand-600/40 transition"
                                            @if($product->stock <= 0) disabled title="Stok habis" @endif>
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </form>

                                    @hasanyrole('Super Admin|Staff Gudang')
                                    <a href="{{ route('products.edit', $product) }}" class="text-brand-400 hover:text-brand-300 text-sm transition">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus produk {{ $product->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">
                                            <i class="fa-solid fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-500">
                                <i class="fa-solid fa-boxes-stacked text-3xl mb-2 block"></i>
                                Belum ada produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>{{ $products->links() }}</div>
</div>
@endsection
