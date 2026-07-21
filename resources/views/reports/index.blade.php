@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Laporan Mutasi Stok</h1>
        @can('role', 'Super Admin')
        @endcan
        @if(auth()->user()->hasRole('Super Admin'))
        <a
            href="{{ route('reports.export-pdf', ['month' => $month, 'year' => $year]) }}"
            target="_blank"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition shadow-lg"
        >
            <i class="fa-solid fa-file-pdf"></i>
            Ekspor PDF
        </a>
        @endif
    </div>

    {{-- Form Filter --}}
    <form method="GET" action="{{ route('reports.index') }}" class="glass-card flex flex-wrap items-end gap-4">
        <div>
            <label class="block mb-1.5 text-xs font-medium text-slate-400" for="month">Bulan</label>
            <select name="month" id="month"
                class="rounded-xl bg-slate-800/60 border border-white/10 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($m == $month)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block mb-1.5 text-xs font-medium text-slate-400" for="year">Tahun</label>
            <select name="year" id="year"
                class="rounded-xl bg-slate-800/60 border border-white/10 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                @foreach($years as $y)
                    <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-semibold text-slate-100 hover:bg-slate-600/60 transition">
            <i class="fa-solid fa-filter"></i>
            Filter
        </button>
    </form>

    @if($errors->any())
        <div class="glass-card border border-red-500/30 bg-red-500/10">
            <ul class="space-y-1 text-sm text-red-400">
                @foreach($errors->all() as $error)
                    <li><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-stat-card label="Total Masuk" value="{{ number_format($summary['total_in']) }}" icon="fa-solid fa-arrow-down" />
        <x-stat-card label="Total Keluar" value="{{ number_format($summary['total_out']) }}" icon="fa-solid fa-arrow-up" />
        <x-stat-card label="Jumlah Transaksi" value="{{ number_format($summary['total_transactions']) }}" icon="fa-solid fa-receipt" />
    </div>

    {{-- Tabel Mutasi --}}
    <div class="glass-card overflow-hidden p-0">
        <div class="border-b border-white/10 px-5 py-4">
            <p class="font-semibold text-slate-100">
                Data Mutasi —
                <span class="text-brand-400">
                    {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                </span>
            </p>
        </div>

        @if($mutations->isEmpty())
            <div class="flex flex-col items-center py-16 text-center">
                <i class="fa-solid fa-folder-open text-4xl text-slate-600 mb-3"></i>
                <p class="text-sm text-slate-400">Tidak ada data mutasi untuk periode ini.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-left text-xs text-slate-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Produk</th>
                        <th class="px-5 py-3 font-medium">Tipe</th>
                        <th class="px-5 py-3 font-medium">Jumlah</th>
                        <th class="px-5 py-3 font-medium">Stok Sebelum</th>
                        <th class="px-5 py-3 font-medium">Stok Sesudah</th>
                        <th class="px-5 py-3 font-medium">Oleh</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($mutations as $i => $mutation)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-5 py-3 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-medium text-slate-100">{{ $mutation->product->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            @if($mutation->type === 'in')
                                <x-badge status="in-stock">Masuk</x-badge>
                            @else
                                <x-badge status="out">Keluar</x-badge>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-300">{{ number_format($mutation->quantity) }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ number_format($mutation->stock_before ?? 0) }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ number_format($mutation->stock_after ?? 0) }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $mutation->user->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $mutation->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
