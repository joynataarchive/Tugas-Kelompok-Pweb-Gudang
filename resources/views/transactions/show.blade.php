@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('transactions.index') }}" class="text-slate-400 hover:text-slate-200 transition"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-100">Detail Transaksi #{{ $transaction->id }}</h1>
    </div>

    <x-card title="Informasi Transaksi">
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-slate-400">User</p>
                <p class="font-medium text-slate-100">{{ $transaction->user->name }}</p>
            </div>
            <div>
                <p class="text-slate-400">Tanggal</p>
                <p class="font-medium text-slate-100">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-slate-400">Status</p>
                <x-badge status="{{ $transaction->status === 'completed' ? 'in-stock' : 'out' }}">{{ ucfirst($transaction->status) }}</x-badge>
            </div>
            <div>
                <p class="text-slate-400">Total</p>
                <p class="text-xl font-bold text-brand-400">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </x-card>

    <x-card title="Item Transaksi">
        <table class="w-full text-sm text-slate-300">
            <thead>
                <tr class="border-b border-white/10 text-xs text-slate-400 uppercase">
                    <th class="pb-2 text-left">Produk</th>
                    <th class="pb-2 text-right">Qty</th>
                    <th class="pb-2 text-right">Harga</th>
                    <th class="pb-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($transaction->items as $item)
                    <tr>
                        <td class="py-2.5 font-medium text-slate-100">{{ $item->product->name }}</td>
                        <td class="py-2.5 text-right">{{ number_format($item->quantity) }}</td>
                        <td class="py-2.5 text-right text-slate-400">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-2.5 text-right font-medium">Rp {{ number_format($item->subtotal(), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</div>
@endsection
