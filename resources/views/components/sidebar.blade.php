{{--
    Sidebar navigasi utama.
    - Desktop (md+): tampil permanen, sticky di kiri.
    - Mobile: off-canvas, dibuka lewat tombol hamburger di <x-navbar> (state dibagi via Alpine.store('sidebar')).
    - Props:
        active  => key menu yang lagi aktif, contoh: <x-sidebar active="produk" />
                   kalau nggak diisi, dideteksi otomatis dari segment pertama URL.

    Catatan: href di bawah masih pakai path polos (url('/produk'), dst).
    Sesuaikan ke route() yang sebenarnya dipakai di project (mis. route('produk.index')).
--}}
@props(['active' => null])
@php
$navItems = [
    ['key' => 'dashboard',       'label' => 'Dashboard',   'icon' => 'fa-solid fa-gauge-high',    'href' => url('/dashboard'),       'role' => null],
    ['key' => 'products',        'label' => 'Produk',      'icon' => 'fa-solid fa-boxes-stacked',  'href' => route('products.index'), 'role' => null],
    ['key' => 'stock-mutations', 'label' => 'Mutasi Stok', 'icon' => 'fa-solid fa-right-left',     'href' => route('stock-mutations.index'), 'role' => null],
    ['key' => 'reports',         'label' => 'Laporan',     'icon' => 'fa-solid fa-chart-column',   'href' => url('/reports'),         'role' => 'Super Admin'],
];
$current = $active ?? request()->segment(1);
@endphp

{{-- Overlay gelap di mobile saat sidebar terbuka --}}
<div
    x-data
    x-init="if (!Alpine.store('sidebar')) Alpine.store('sidebar', { open: false })"
    x-show="$store.sidebar.open"
    x-transition.opacity
    @click="$store.sidebar.open = false"
    class="fixed inset-0 z-30 bg-slate-950/70 md:hidden"
    style="display: none;"
></div>

<aside
    x-data
    x-init="if (!Alpine.store('sidebar')) Alpine.store('sidebar', { open: false })"
    :class="$store.sidebar.open ? 'translate-x-0' : '-translate-x-full'"
    class="glass-panel w-64 transform overflow-y-auto p-4 transition-transform duration-200 ease-in-out max-md:fixed max-md:inset-y-0 max-md:left-0 max-md:z-40 md:sticky md:top-0 md:h-screen md:translate-x-0 md:shrink-0"
>
    <div class="mb-6 flex items-center gap-2 px-2">
        <i class="fa-solid fa-warehouse text-xl text-brand-400"></i>
        <span class="font-sans text-lg font-semibold text-slate-100">GudangSaaS</span>
    </div>

    <nav class="space-y-1">
        @foreach ($navItems as $item)
            @if (!$item['role'] || (auth()->check() && auth()->user()->hasRole($item['role'])))
                <a
                    href="{{ $item['href'] }}"
                    @click="$store.sidebar.open = false"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                        {{ $current === $item['key']
                            ? 'bg-brand-500/10 text-brand-400 border border-brand-500/20'
                            : 'text-slate-300 hover:bg-slate-700/40 hover:text-slate-100' }}"
                >
                    <i class="{{ $item['icon'] }} w-4 text-center"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</aside>
