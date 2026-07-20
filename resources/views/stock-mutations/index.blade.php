@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-slate-100">Mutasi Stok</h1>
    <x-button variant="primary" onclick="window.location='{{ route('stock-mutations.create') }}'">
        <i class="fa-solid fa-plus mr-1"></i> Catat Mutasi
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
                <th class="py-2">Tanggal</th>
                <th class="py-2">Produk</th>
                <th class="py-2">Tipe</th>
                <th class="py-2">Jumlah</th>
                <th class="py-2">Stok Sebelum → Sesudah</th>
                <th class="py-2">Dicatat oleh</th>
                <th class="py-2">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mutations as $mutation)
                <tr class="border-b border-white/5">
                    <td class="py-2">{{ $mutation->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-2 text-slate-100">{{ $mutation->product->name }}</td>
                    <td class="py-2">
                        @if($mutation->type === 'in')
                            <x-badge status="in-stock">Masuk</x-badge>
                        @else
                            <x-badge status="out">Keluar</x-badge>
                        @endif
                    </td>
                    <td class="py-2">{{ $mutation->quantity }}</td>
                    <td class="py-2">{{ $mutation->stock_before }} → {{ $mutation->stock_after }}</td>
                    <td class="py-2">{{ $mutation->user->name ?? '-' }}</td>
                    <td class="py-2 text-slate-400">{{ $mutation->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-card>

<div class="mt-4">
    {{ $mutations->links() }}
</div>
@endsection
