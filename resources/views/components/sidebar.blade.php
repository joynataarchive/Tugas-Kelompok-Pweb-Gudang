{{--
    Sidebar navigasi utama.
    - Desktop (md+): tampil permanen, sticky di kiri.
    - Mobile: off-canvas, dibuka lewat tombol hamburger di <x-navbar> (state dibagi via Alpine.store('sidebar')).
    - Props:
        active  => key menu yang lagi aktif, contoh: <x-sidebar active="produk" />
                   kalau nggak diisi, dideteksi otomatis dari segment pertama URL.

    Catatan: role => null  = semua role bisa lihat.
             role => ['Super Admin'] = hanya role tertentu yang bisa lihat.
--}}
@props(['active' => null])
@php
$navItems = [
    // ── Utama ──────────────────────────────────────────────────────────────
    ['key' => 'dashboard',       'label' => 'Dashboard',          'icon' => 'fa-solid fa-gauge-high',       'href' => route('dashboard'),              'role' => null],

    // ── Inventori ───────────────────────────────────────────────────────────
    ['key' => 'products',        'label' => 'Produk',             'icon' => 'fa-solid fa-boxes-stacked',    'href' => route('products.index'),         'role' => null],
    ['key' => 'stock-mutations', 'label' => 'Mutasi Stok',        'icon' => 'fa-solid fa-right-left',       'href' => route('stock-mutations.index'),  'role' => null],
    ['key' => 'categories',      'label' => 'Kategori',           'icon' => 'fa-solid fa-tag',              'href' => route('categories.index'),       'role' => ['Super Admin', 'Staff Gudang']],
    ['key' => 'suppliers',       'label' => 'Supplier',           'icon' => 'fa-solid fa-truck',            'href' => route('suppliers.index'),        'role' => ['Super Admin', 'Staff Gudang']],

    // ── Transaksi & Permintaan ──────────────────────────────────────────────
    ['key' => 'item-requests',   'label' => 'Permintaan Barang',  'icon' => 'fa-solid fa-inbox',            'href' => route('item-requests.index'),    'role' => null],
    ['key' => 'transactions',    'label' => 'Transaksi',          'icon' => 'fa-solid fa-receipt',          'href' => route('transactions.index'),     'role' => null],

    // ── Kendaraan ───────────────────────────────────────────────────────────
    ['key' => 'vehicles',        'label' => 'Master Kendaraan',   'icon' => 'fa-solid fa-car',              'href' => route('vehicles.index'),         'role' => ['Super Admin', 'Staff Gudang']],
    ['key' => 'vehicle-loans',   'label' => 'Peminjaman Kendaraan','icon' => 'fa-solid fa-key',             'href' => route('vehicle-loans.index'),    'role' => null],

    // ── Manajemen ───────────────────────────────────────────────────────────
    ['key' => 'users',           'label' => 'Kelola User',        'icon' => 'fa-solid fa-users-gear',       'href' => route('users.index'),            'role' => ['Super Admin']],
    ['key' => 'roles',           'label' => 'Roles & Permission', 'icon' => 'fa-solid fa-shield-halved',    'href' => route('roles.index'),            'role' => ['Super Admin']],

    // ── Laporan ─────────────────────────────────────────────────────────────
    ['key' => 'reports',         'label' => 'Laporan',            'icon' => 'fa-solid fa-chart-column',     'href' => route('reports.index'),          'role' => null],
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
            @php
                // role null = semua role boleh.
                // role berupa array = cek hasAnyRole.
                $canSee = !$item['role'] || (auth()->check() && auth()->user()->hasAnyRole($item['role']));
            @endphp
            @if($canSee)
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

    {{-- Profil cepat di bawah sidebar --}}
    <div class="mt-6 border-t border-white/10 pt-4">
        <a href="{{ route('profile.index') }}"
           @click="$store.sidebar.open = false"
           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700/40 hover:text-slate-100 transition
               {{ $current === 'profile' ? 'bg-brand-500/10 text-brand-400 border border-brand-500/20' : '' }}">
            <i class="fa-solid fa-circle-user w-4 text-center"></i>
            <span>Ubah Akun</span>
        </a>
    </div>
</aside>
