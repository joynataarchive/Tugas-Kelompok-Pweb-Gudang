<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GudangSaaS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="flex">
        {{-- TEMP sidebar — ganti dengan punya Rava (Role 1) begitu selesai --}}
        <aside class="w-56 min-h-screen glass-panel m-2 p-4 hidden md:block">
            <p class="text-brand-400 font-semibold mb-4">GudangSaaS</p>
            <nav class="space-y-2 text-sm text-slate-300">
                <a href="{{ route('products.index') }}" class="block hover:text-brand-400">Produk</a>
                <a href="{{ route('stock-mutations.index') }}" class="block hover:text-brand-400">Mutasi Stok</a>
            </nav>
        </aside>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
