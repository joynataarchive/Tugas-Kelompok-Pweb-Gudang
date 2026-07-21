<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - GudangSaaS</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-surface p-4">
    <div class="w-full max-w-md">
        <!-- Logo / Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-brand-500/10 border border-brand-500/20 text-brand-400 mb-3">
                <i class="fa-solid fa-warehouse text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-100 font-sans">GudangSaaS</h2>
            <p class="text-slate-400 text-sm mt-1">Sistem Manajemen Inventaris Gudang</p>
        </div>

        <x-card title="Masuk ke Akun Anda" class="p-6">
            <form method="POST" action="{{ route('login') }}" class="mt-4">
                @csrf

                <!-- Email Input -->
                <x-input 
                    label="Alamat Email" 
                    name="email" 
                    type="email" 
                    placeholder="nama@perusahaan.com" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                />

                <!-- Password Input -->
                <x-input 
                    label="Kata Sandi" 
                    name="password" 
                    type="password" 
                    placeholder="••••••••" 
                    required 
                />

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-slate-300 select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-white/10 text-brand-500 focus:ring-brand-500 focus:ring-offset-slate-900 mr-2">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <x-button type="submit" variant="primary" class="w-full justify-center flex items-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk
                </x-button>
            </form>
        </x-card>
    </div>
</body>
</html>
