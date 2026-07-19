{{--
    Navbar / top bar.
    - Tombol hamburger cuma muncul di mobile (md:hidden), togglenya lewat Alpine.store('sidebar')
      yang didaftarkan di <x-sidebar>. Jadi pastikan <x-sidebar> ikut dirender di layout.
    - Prop `title` optional buat judul halaman (mis. "Daftar Produk"); kalau kosong,
      tampil logo GudangSaaS (khusus mobile, karena logo desktop sudah ada di sidebar).
--}}
@props(['title' => null])

<header
    x-data
    class="glass-panel sticky top-0 z-20 mb-6 flex items-center justify-between gap-4 px-4 py-3"
>
    <div class="flex items-center gap-3">
        <button
            @click="$store.sidebar.open = !$store.sidebar.open"
            class="text-slate-100 md:hidden"
            aria-label="Buka menu"
        >
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        @if($title)
            <h1 class="text-lg font-semibold text-slate-100">{{ $title }}</h1>
        @else
            <div class="flex items-center gap-2 md:hidden">
                <i class="fa-solid fa-warehouse text-brand-400"></i>
                <span class="font-semibold text-slate-100">GudangSaaS</span>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4">
        <button class="relative text-slate-300 hover:text-slate-100" aria-label="Notifikasi">
            <i class="fa-regular fa-bell text-lg"></i>
            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-semibold text-white">
                3
            </span>
        </button>

        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
            </button>

            <div
                x-show="open"
                x-transition
                style="display: none;"
                class="glass-panel absolute right-0 z-50 mt-2 w-44 overflow-hidden !rounded-xl py-1"
            >
                <a href="{{ url('/profil') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/40 hover:text-slate-100">
                    <i class="fa-solid fa-user mr-2"></i> Profil
                </a>
                <a href="{{ url('/pengaturan') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/40 hover:text-slate-100">
                    <i class="fa-solid fa-gear mr-2"></i> Pengaturan
                </a>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-400 hover:bg-red-500/10">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
