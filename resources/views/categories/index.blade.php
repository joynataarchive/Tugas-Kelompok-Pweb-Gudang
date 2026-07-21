@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Kelola Kategori</h1>
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-500/15 border border-red-500/30 px-4 py-3 text-sm text-red-300">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Search + Tabel --}}
    <x-card>
        {{-- Search --}}
        <form method="GET" action="{{ route('categories.index') }}" class="mb-4 flex gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama kategori..."
                class="flex-1 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60"
            >
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <table class="w-full text-left text-sm text-slate-300">
            <thead>
                <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                    <th class="py-2 pr-4">#</th>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Deskripsi</th>
                    <th class="py-2 pr-4">Jumlah Produk</th>
                    <th class="py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($categories as $i => $category)
                    <tr class="hover:bg-white/5 transition">
                        <td class="py-2.5 pr-4 text-slate-500">{{ $categories->firstItem() + $i }}</td>
                        <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $category->name }}</td>
                        <td class="py-2.5 pr-4 text-slate-400 max-w-xs truncate">{{ $category->description ?: '-' }}</td>
                        <td class="py-2.5 pr-4">
                            <span class="rounded-full bg-brand-500/15 border border-brand-500/20 px-2 py-0.5 text-xs font-medium text-brand-300">
                                {{ $category->products_count }} produk
                            </span>
                        </td>
                        <td class="py-2.5 text-right space-x-3">
                            <a href="{{ route('categories.edit', $category) }}" class="text-brand-400 hover:text-brand-300 text-sm transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin hapus kategori {{ $category->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                            Belum ada kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div>{{ $categories->links() }}</div>
</div>
@endsection
