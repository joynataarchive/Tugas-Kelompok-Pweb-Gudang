@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Mutasi Stok</h1>
        <a href="{{ route('stock-mutations.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Catat Mutasi
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <x-card>
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('stock-mutations.index') }}" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk..."
                class="flex-1 min-w-40 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <select name="type"
                class="rounded-xl bg-slate-800/60 border border-white/10 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                <option value="">Semua Tipe</option>
                <option value="in" @selected(request('type')==='in')>Masuk</option>
                <option value="out" @selected(request('type')==='out')>Keluar</option>
            </select>
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('stock-mutations.index') }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Tipe</th>
                        <th class="py-2 pr-4">Jumlah</th>
                        <th class="py-2 pr-4">Stok Sebelum → Sesudah</th>
                        <th class="py-2 pr-4">Dicatat oleh</th>
                        <th class="py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($mutations as $mutation)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-xs text-slate-400">{{ $mutation->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $mutation->product->name ?? '-' }}</td>
                            <td class="py-2.5 pr-4">
                                @if($mutation->type === 'in')
                                    <x-badge status="in-stock">Masuk</x-badge>
                                @else
                                    <x-badge status="out">Keluar</x-badge>
                                @endif
                            </td>
                            <td class="py-2.5 pr-4">{{ number_format($mutation->quantity) }}</td>
                            <td class="py-2.5 pr-4 font-mono text-xs">
                                {{ $mutation->stock_before }} → {{ $mutation->stock_after }}
                            </td>
                            <td class="py-2.5 pr-4 text-slate-400">{{ $mutation->user->name ?? '-' }}</td>
                            <td class="py-2.5 text-slate-400 max-w-xs truncate">{{ $mutation->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <i class="fa-solid fa-right-left text-3xl mb-2 block"></i>
                                Belum ada data mutasi stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>{{ $mutations->links() }}</div>
</div>
@endsection
