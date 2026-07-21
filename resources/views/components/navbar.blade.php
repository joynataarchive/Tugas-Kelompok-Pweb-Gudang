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

    <div class="flex items-center gap-3">
        <button class="flex items-center justify-center h-8 w-8 rounded-lg hover:bg-slate-800/40 text-slate-300 hover:text-slate-100 transition" aria-label="Notifikasi">
            <i class="fa-regular fa-bell text-lg"></i>
        </button>

        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
            <button @click="open = !open" class="flex items-center gap-2 text-slate-100">
                <span class="w-9 h-9 rounded-full bg-brand-600 flex items-center justify-center font-semibold text-white">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </span>
                <span class="hidden md:inline">{{ auth()->user()->name ?? 'User' }}</span>
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>

            <div
                x-show="open"
                x-transition
                x-cloak
                class="absolute right-0 top-full mt-2 w-56 bg-slate-800 border border-white/10 rounded-xl shadow-xl z-50 overflow-hidden"
            >
                <div class="px-4 py-3 border-b border-white/10">
                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                    <p class="text-sm font-semibold text-slate-100">{{ auth()->user()->name ?? 'User' }}</p>
                </div>
                <a href="{{ url('/profil') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/60">
                    <i class="fa-solid fa-user mr-2"></i> Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-slate-700/60">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
