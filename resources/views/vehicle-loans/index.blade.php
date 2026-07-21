@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Peminjaman Kendaraan</h1>
        <a href="{{ route('vehicle-loans.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-car"></i> Pinjam Kendaraan
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-500/15 border border-red-500/30 px-4 py-3 text-sm text-red-300">{{ session('error') }}</div>
    @endif

    <x-card>
        <form method="GET" action="{{ route('vehicle-loans.index') }}" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kendaraan..."
                class="flex-1 min-w-40 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <select name="status" class="rounded-xl bg-slate-800/60 border border-white/10 px-3 py-2 text-sm text-slate-100 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="borrowed" @selected(request('status')==='borrowed')>Dipinjam</option>
                <option value="returned" @selected(request('status')==='returned')>Dikembalikan</option>
            </select>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">#</th>
                        <th class="py-2 pr-4">Kendaraan</th>
                        @if($isAdmin) <th class="py-2 pr-4">Peminjam</th> @endif
                        <th class="py-2 pr-4">Keperluan</th>
                        <th class="py-2 pr-4">Tanggal Pinjam</th>
                        <th class="py-2 pr-4">Dikembalikan</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($loans as $i => $loan)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-500">{{ $loans->firstItem() + $i }}</td>
                            <td class="py-2.5 pr-4 font-medium text-slate-100">
                                {{ $loan->vehicle->name }}<br>
                                <span class="text-xs text-slate-500 font-mono">{{ $loan->vehicle->plate_number }}</span>
                            </td>
                            @if($isAdmin)
                            <td class="py-2.5 pr-4 text-slate-400">{{ $loan->user->name }}</td>
                            @endif
                            <td class="py-2.5 pr-4 text-slate-400 max-w-xs truncate">{{ $loan->purpose }}</td>
                            <td class="py-2.5 pr-4 text-slate-400 text-xs">{{ $loan->borrowed_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2.5 pr-4 text-slate-400 text-xs">{{ $loan->returned_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="py-2.5 pr-4">
                                @if($loan->status === 'borrowed')
                                    <x-badge status="low-stock">Dipinjam</x-badge>
                                @else
                                    <x-badge status="in-stock">Dikembalikan</x-badge>
                                @endif
                            </td>
                            <td class="py-2.5 text-right">
                                @if($loan->status === 'borrowed' && ($isAdmin || $loan->user_id === auth()->id()))
                                    <form action="{{ route('vehicle-loans.return', $loan) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Konfirmasi pengembalian kendaraan?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium transition">
                                            <i class="fa-solid fa-rotate-left mr-1"></i>Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-600">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $isAdmin ? 8 : 7 }}" class="py-12 text-center text-slate-500">Belum ada data peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div>{{ $loans->links() }}</div>
</div>
@endsection
