@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Permintaan Barang</h1>
        <a href="{{ route('item-requests.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Ajukan Permintaan
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <x-card>
        {{-- Filter --}}
        <form method="GET" action="{{ route('item-requests.index') }}" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                class="flex-1 min-w-40 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <select name="status"
                class="rounded-xl bg-slate-800/60 border border-white/10 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status')==='pending')>Pending</option>
                <option value="approved" @selected(request('status')==='approved')>Disetujui</option>
                <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
            </select>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('item-requests.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">#</th>
                        @if($isAdmin) <th class="py-2 pr-4">Pengaju</th> @endif
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Jumlah</th>
                        <th class="py-2 pr-4">Catatan</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4">Tanggal</th>
                        @if($isAdmin) <th class="py-2 text-right">Aksi</th> @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($itemRequests as $i => $req)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-500">{{ $itemRequests->firstItem() + $i }}</td>
                            @if($isAdmin)
                            <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $req->user->name }}</td>
                            @endif
                            <td class="py-2.5 pr-4">{{ $req->product->name ?? '-' }}</td>
                            <td class="py-2.5 pr-4">{{ number_format($req->quantity) }}</td>
                            <td class="py-2.5 pr-4 text-slate-400 max-w-xs truncate">{{ $req->note ?: '-' }}</td>
                            <td class="py-2.5 pr-4">
                                @if($req->status === 'pending')
                                    <x-badge status="low-stock">Pending</x-badge>
                                @elseif($req->status === 'approved')
                                    <x-badge status="in-stock">Disetujui</x-badge>
                                @else
                                    <x-badge status="out">Ditolak</x-badge>
                                @endif
                            </td>
                            <td class="py-2.5 pr-4 text-slate-400 text-xs">{{ $req->created_at->format('d/m/Y') }}</td>
                            @if($isAdmin)
                            <td class="py-2.5 text-right">
                                @if($req->status === 'pending')
                                    <div class="inline-flex gap-2">
                                        <form action="{{ route('item-requests.verify', $req) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="approved">
                                            <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium transition">
                                                <i class="fa-solid fa-check mr-1"></i>Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('item-requests.verify', $req) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Tolak permintaan ini?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="rejected">
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium transition">
                                                <i class="fa-solid fa-xmark mr-1"></i>Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-600">Sudah diproses</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 6 }}" class="py-12 text-center text-slate-500">
                                Belum ada permintaan barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div>{{ $itemRequests->links() }}</div>
</div>
@endsection
