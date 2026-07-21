@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Keranjang Belanja</h1>
        <a href="{{ route('products.index') }}" class="text-sm text-brand-400 hover:text-brand-300 transition">← Kembali Belanja</a>
    </div>

    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="rounded-xl {{ $type==='success'?'bg-emerald-500/15 border-emerald-500/30 text-emerald-300':'bg-red-500/15 border-red-500/30 text-red-300' }} border px-4 py-3 text-sm">{{ session($type) }}</div>
        @endif
    @endforeach

    @if($cart->items->isEmpty())
        <div class="glass-card flex flex-col items-center py-16 text-center">
            <i class="fa-solid fa-cart-shopping text-4xl text-slate-600 mb-3"></i>
            <p class="text-slate-400">Keranjang Anda kosong.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
    @else
        <x-card>
            <table class="w-full text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase">
                        <th class="pb-2 text-left">Produk</th>
                        <th class="pb-2 text-center">Qty</th>
                        <th class="pb-2 text-right">Harga Satuan</th>
                        <th class="pb-2 text-right">Subtotal</th>
                        <th class="pb-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($cart->items as $item)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-3 font-medium text-slate-100">{{ $item->product->name }}</td>
                            <td class="py-3 text-center">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                        class="w-16 rounded-lg bg-slate-800/60 border border-white/10 px-2 py-1 text-center text-sm text-slate-100 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                    <button type="submit" class="text-xs text-brand-400 hover:text-brand-300 transition">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 text-right text-slate-400">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                            <td class="py-3 text-right font-medium text-slate-100">Rp {{ number_format($item->subtotal(), 0, ',', '.') }}</td>
                            <td class="py-3 text-right pl-4">
                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-white/10">
                        <td colspan="3" class="pt-4 text-right text-slate-400 font-medium">Total:</td>
                        <td class="pt-4 text-right text-xl font-bold text-brand-400">
                            Rp {{ number_format($cart->total(), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </x-card>

        <div class="flex justify-end">
            <form action="{{ route('cart.checkout') }}" method="POST"
                  onsubmit="return confirm('Konfirmasi checkout? Stok akan langsung dikurangi.')">
                @csrf
                <x-button variant="primary" type="submit" class="px-8 py-3">
                    <i class="fa-solid fa-bag-shopping mr-2"></i>Checkout — Rp {{ number_format($cart->total(), 0, ',', '.') }}
                </x-button>
            </form>
        </div>
    @endif
</div>
@endsection
