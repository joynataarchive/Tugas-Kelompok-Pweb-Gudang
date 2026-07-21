<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GudangSaaS — Sistem Manajemen Inventaris Modern</title>
    <meta name="description" content="GudangSaaS adalah platform manajemen inventaris gudang modern berbasis cloud. Kelola produk, mutasi stok, dan laporan dengan mudah dan efisien.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden">

    {{-- Navbar Landing --}}
    <nav x-data="{ open: false }" class="sticky top-0 z-50 glass-panel rounded-none border-x-0 border-t-0">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-warehouse text-xl text-brand-400"></i>
                <span class="font-sans text-lg font-bold text-slate-100">GudangSaaS</span>
            </div>
            <div class="hidden items-center gap-6 md:flex">
                <a href="#features" class="text-sm text-slate-300 hover:text-slate-100 transition">Fitur</a>
                <a href="#how-it-works" class="text-sm text-slate-300 hover:text-slate-100 transition">Cara Kerja</a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition shadow-lg">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </a>
            </div>
            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="md:hidden text-slate-300 hover:text-slate-100">
                <i x-show="!open" class="fa-solid fa-bars text-xl"></i>
                <i x-show="open" x-cloak class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        {{-- Mobile menu --}}
        <div x-show="open" x-cloak x-transition class="md:hidden border-t border-white/10 px-6 py-4 space-y-3">
            <a href="#features" @click="open=false" class="block text-sm text-slate-300 hover:text-slate-100">Fitur</a>
            <a href="#how-it-works" @click="open=false" class="block text-sm text-slate-300 hover:text-slate-100">Cara Kerja</a>
            <a href="{{ route('login') }}" class="block text-sm font-semibold text-brand-400">Masuk ke Dashboard →</a>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        {{-- Background decorative blobs --}}
        <div class="pointer-events-none absolute -top-32 -left-32 h-96 w-96 rounded-full bg-brand-600/20 blur-3xl"></div>
        <div class="pointer-events-none absolute top-20 right-0 h-64 w-64 rounded-full bg-brand-400/10 blur-3xl"></div>

        <div class="mx-auto max-w-6xl px-6 py-24 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-brand-500/30 bg-brand-500/10 px-4 py-1.5 text-sm font-medium text-brand-300 mb-6">
                <i class="fa-solid fa-bolt text-brand-400"></i>
                Sistem Inventaris Generasi Baru
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-100 leading-tight mb-6">
                Kelola Gudang Anda<br>
                <span class="bg-gradient-to-r from-brand-400 to-brand-600 bg-clip-text text-transparent">
                    Lebih Cerdas & Efisien
                </span>
            </h1>
            <p class="mx-auto max-w-2xl text-lg text-slate-400 mb-10 leading-relaxed">
                GudangSaaS adalah platform manajemen inventaris berbasis cloud yang membantu bisnis Anda melacak stok, mutasi barang, dan laporan secara real-time — dari mana saja.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-8 py-3.5 text-base font-semibold text-white hover:bg-brand-500 transition shadow-xl shadow-brand-600/30">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Mulai Sekarang — Gratis
                </a>
                <a href="#features"
                   class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-slate-800/50 px-8 py-3.5 text-base font-semibold text-slate-200 hover:bg-slate-700/50 transition">
                    Pelajari Fitur
                    <i class="fa-solid fa-arrow-down text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="mx-auto max-w-6xl px-6 py-20">
        <div class="mb-14 text-center">
            <h2 class="text-3xl font-bold text-slate-100 mb-3">Semua yang Anda Butuhkan</h2>
            <p class="text-slate-400">Fitur lengkap dalam satu platform yang terintegrasi</p>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @php
            $features = [
                ['icon' => 'fa-solid fa-boxes-stacked', 'title' => 'Manajemen Produk', 'desc' => 'Kelola ribuan SKU produk dengan kategori, supplier, dan harga — lengkap dengan pencarian dan filter canggih.'],
                ['icon' => 'fa-solid fa-right-left', 'title' => 'Mutasi Stok Real-Time', 'desc' => 'Catat setiap transaksi masuk/keluar secara akurat. Stok diperbarui otomatis dengan proteksi transaksi atomik.'],
                ['icon' => 'fa-solid fa-chart-column', 'title' => 'Dashboard & Analitik', 'desc' => 'Lihat tren mutasi 7 hari, KPI nilai stok, dan peringatan stok rendah dalam satu tampilan yang intuitif.'],
                ['icon' => 'fa-solid fa-file-pdf', 'title' => 'Ekspor Laporan PDF', 'desc' => 'Buat laporan mutasi bulanan dan unduh sebagai PDF langsung dari browser — siap cetak, tanpa keahlian teknis.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Kontrol Akses RBAC', 'desc' => 'Tetapkan peran Super Admin, Staff Gudang, atau Supplier — setiap akun hanya melihat data yang relevan baginya.'],
                ['icon' => 'fa-solid fa-plug', 'title' => 'REST API Lengkap', 'desc' => 'Integrasikan GudangSaaS dengan sistem Anda lewat API berformat JSON yang aman dan terdokumentasi dengan baik.'],
            ];
            @endphp

            @foreach($features as $f)
            <div class="glass-card hover:bg-slate-800/50 hover:-translate-y-1 transition-all duration-200 group">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-400 group-hover:bg-brand-500/20 transition">
                    <i class="{{ $f['icon'] }} text-lg"></i>
                </div>
                <h3 class="mb-2 font-semibold text-slate-100">{{ $f['title'] }}</h3>
                <p class="text-sm text-slate-400 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 bg-slate-800/20">
        <div class="mx-auto max-w-4xl px-6 text-center">
            <h2 class="text-3xl font-bold text-slate-100 mb-3">Cara Kerja</h2>
            <p class="text-slate-400 mb-14">Mulai dalam 3 langkah mudah</p>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @php
                $steps = [
                    ['num' => '1', 'title' => 'Daftar & Login', 'desc' => 'Minta akun ke Super Admin, lalu login dengan email & password yang diberikan.'],
                    ['num' => '2', 'title' => 'Atur Inventaris', 'desc' => 'Tambahkan produk, atur kategori dan supplier, lalu mulai catat mutasi stok.'],
                    ['num' => '3', 'title' => 'Pantau & Ekspor', 'desc' => 'Pantau dashboard real-time dan ekspor laporan PDF kapan saja dibutuhkan.'],
                ];
                @endphp
                @foreach($steps as $step)
                <div class="flex flex-col items-center">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-600 text-xl font-bold text-white shadow-lg shadow-brand-600/30">
                        {{ $step['num'] }}
                    </div>
                    <h3 class="mb-2 font-semibold text-slate-100">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="mx-auto max-w-6xl px-6 py-20">
        <div class="glass-panel relative overflow-hidden p-10 text-center">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-600/20 to-transparent"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold text-slate-100 mb-3">Siap Mulai?</h2>
                <p class="text-slate-400 mb-8">Masuk ke dashboard dan kelola inventaris Anda sekarang juga.</p>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-8 py-3.5 text-base font-semibold text-white hover:bg-brand-500 transition shadow-xl shadow-brand-600/40">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk ke GudangSaaS
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-white/10 py-8 text-center text-sm text-slate-500">
        <p>© {{ date('Y') }} GudangSaaS. Dibuat dengan <span class="text-brand-400">♥</span> menggunakan Laravel & Tailwind CSS.</p>
    </footer>

</body>
</html>
