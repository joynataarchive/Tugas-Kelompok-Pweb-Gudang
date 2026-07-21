@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Dashboard</h1>
        <span class="text-sm text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    @if($isSupplier && !$supplierLinked)
        {{-- Empty state: akun supplier belum terhubung ke entitas supplier --}}
        <div class="glass-card flex flex-col items-center py-16 text-center">
            <i class="fa-solid fa-link-slash text-5xl text-slate-500 mb-4"></i>
            <p class="text-lg font-semibold text-slate-300">Akun Supplier Belum Terhubung</p>
            <p class="text-sm text-slate-400 mt-1">Hubungi Super Admin untuk menghubungkan akun Anda ke entitas supplier.</p>
        </div>
    @else

    {{-- Notifikasi permintaan pending --}}
    @if($pendingRequests > 0)
        <div class="flex items-center gap-3 rounded-xl bg-amber-500/10 border border-amber-500/30 px-4 py-3">
            <i class="fa-solid fa-bell text-amber-400 text-lg"></i>
            <div>
                <p class="text-sm font-semibold text-amber-300">
                    {{ $pendingRequests }} permintaan barang menunggu verifikasi
                </p>
                <a href="{{ route('item-requests.index') }}" class="text-xs text-amber-400 hover:text-amber-300 transition">
                    Lihat Permintaan →
                </a>
            </div>
        </div>
    @endif

    {{-- KPI Cards — Baris 1: Produk, Supplier, Nilai Stok --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-stat-card
            label="Total Produk"
            value="{{ number_format($totalProducts) }}"
            icon="fa-solid fa-boxes-stacked"
        />
        @unless($isSupplier)
        <x-stat-card
            label="Total Supplier"
            value="{{ number_format($totalSuppliers) }}"
            icon="fa-solid fa-truck"
        />
        @endunless
        <x-stat-card
            label="Total Nilai Stok"
            value="Rp {{ number_format($totalStockValue, 0, ',', '.') }}"
            icon="fa-solid fa-wallet"
        />
    </div>

    {{-- KPI Cards — Baris 2: Kendaraan, Users, Permintaan Barang, Barang Keluar --}}
    @unless($isSupplier)
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            label="Total Kendaraan"
            value="{{ number_format($totalVehicles) }}"
            icon="fa-solid fa-car"
        />
        <x-stat-card
            label="Total Users"
            value="{{ number_format($totalCustomers) }}"
            icon="fa-solid fa-users"
        />
        <x-stat-card
            label="Permintaan Barang"
            value="{{ number_format($totalItemRequests) }}"
            icon="fa-solid fa-inbox"
        />
        <x-stat-card
            label="Barang Keluar Bulan Ini"
            value="{{ number_format($totalStockOutMonth) }}"
            icon="fa-solid fa-arrow-up-from-bracket"
        />
    </div>

    {{-- Stat tambahan: Total Barang Keluar --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="glass-card flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-500/15 text-red-400">
                <i class="fa-solid fa-arrow-trend-down text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider">Total Barang Keluar</p>
                <p class="text-2xl font-bold text-slate-100">{{ number_format($totalStockOut) }}</p>
            </div>
        </div>
        <div class="glass-card flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-400">
                <i class="fa-solid fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider">Permintaan Pending</p>
                <p class="text-2xl font-bold text-slate-100">{{ number_format($pendingRequests) }}</p>
            </div>
        </div>
    </div>
    @endunless

    {{-- Chart & Low Stock --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Grafik Mutasi 7 Hari --}}
        <div class="glass-card xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-semibold text-slate-100">Tren Mutasi Stok (7 Hari Terakhir)</p>
                <div class="flex items-center gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-brand-400"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-400"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="mutationChart"></canvas>
            </div>
        </div>

        {{-- Produk Stok Rendah --}}
        <div class="glass-card flex flex-col">
            <p class="mb-3 font-semibold text-slate-100">
                Stok Rendah
                @if($lowStockProducts->count())
                    <span class="ml-2 rounded-full bg-amber-500/20 px-2 py-0.5 text-xs font-medium text-amber-300 border border-amber-500/30">
                        {{ $lowStockProducts->count() }}
                    </span>
                @endif
            </p>

            @if($lowStockProducts->isEmpty())
                <div class="flex flex-1 flex-col items-center justify-center py-8 text-center">
                    <i class="fa-solid fa-circle-check text-3xl text-emerald-400 mb-2"></i>
                    <p class="text-sm text-slate-400">Semua stok dalam kondisi aman</p>
                </div>
            @else
                <ul class="space-y-2 overflow-y-auto max-h-52 pr-1">
                    @foreach($lowStockProducts as $product)
                        <li class="flex items-center justify-between rounded-lg px-3 py-2 bg-amber-500/5 border border-amber-500/10">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-100">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->category->name ?? '-' }}</p>
                            </div>
                            <x-badge status="{{ $product->stock <= 0 ? 'out' : 'low-stock' }}" class="ml-2 shrink-0">
                                {{ $product->stock }} {{ $product->unit }}
                            </x-badge>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Barang Paling Populer & Mutasi Terbaru --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Barang Paling Populer --}}
        <div class="glass-card">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-semibold text-slate-100">Barang Paling Populer</p>
                <span class="text-xs text-slate-500">Berdasarkan mutasi keluar</span>
            </div>
            @if($popularProducts->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">Belum ada data mutasi keluar.</p>
            @else
                <ul class="space-y-2">
                    @foreach($popularProducts as $i => $pop)
                        <li class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-white/5 transition">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                                {{ $i === 0 ? 'bg-amber-500/20 text-amber-300' : ($i === 1 ? 'bg-slate-500/20 text-slate-300' : ($i === 2 ? 'bg-orange-700/20 text-orange-400' : 'bg-slate-700/40 text-slate-500')) }}
                                text-xs font-bold">
                                {{ $i + 1 }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-100">{{ $pop->product->name ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 text-sm font-semibold text-brand-400">{{ number_format($pop->total_out) }} keluar</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- 5 Mutasi Terbaru --}}
        <div class="glass-card">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-semibold text-slate-100">Mutasi Stok Terbaru</p>
                <a href="{{ route('stock-mutations.index') }}" class="text-sm text-brand-400 hover:text-brand-300 transition">Lihat Semua →</a>
            </div>

            @if($recentMutations->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">Belum ada data mutasi.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-left text-xs text-slate-400">
                            <th class="pb-2 pr-4 font-medium">Produk</th>
                            <th class="pb-2 pr-4 font-medium">Tipe</th>
                            <th class="pb-2 pr-4 font-medium">Jumlah</th>
                            <th class="pb-2 font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentMutations as $mutation)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-200 font-medium truncate max-w-[120px]">
                                {{ $mutation->product->name ?? '-' }}
                            </td>
                            <td class="py-2.5 pr-4">
                                @if($mutation->type === 'in')
                                    <x-badge status="in-stock">Masuk</x-badge>
                                @else
                                    <x-badge status="out">Keluar</x-badge>
                                @endif
                            </td>
                            <td class="py-2.5 pr-4 text-slate-300">{{ number_format($mutation->quantity) }}</td>
                            <td class="py-2.5 text-slate-400 text-xs">{{ $mutation->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    @endif {{-- end: $supplierLinked --}}
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('mutationChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $chartLabels !!},
            datasets: [
                {
                    label: 'Masuk',
                    data: {!! $chartIn !!},
                    backgroundColor: 'rgba(56, 189, 248, 0.6)',
                    borderColor: 'rgba(56, 189, 248, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Keluar',
                    data: {!! $chartOut !!},
                    backgroundColor: 'rgba(248, 113, 113, 0.6)',
                    borderColor: 'rgba(248, 113, 113, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#94a3b8',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    grid:  { color: 'rgba(255,255,255,0.05)' },
                },
                y: {
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    grid:  { color: 'rgba(255,255,255,0.05)' },
                    beginAtZero: true,
                }
            }
        }
    });
});
</script>
@endsection
