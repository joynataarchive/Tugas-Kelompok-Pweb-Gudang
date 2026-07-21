@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-slate-100">Riwayat Transaksi</h1>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <x-card>
        {{-- Search --}}
        @if($isAdmin)
        <form method="GET" action="{{ route('transactions.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama user..."
                class="flex-1 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('transactions.index') }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">#</th>
                        @if($isAdmin) <th class="py-2 pr-4">User</th> @endif
                        <th class="py-2 pr-4">Total</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $i => $trx)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-500">{{ $transactions->firstItem() + $i }}</td>
                            @if($isAdmin)
                            <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $trx->user->name ?? '-' }}</td>
                            @endif
                            <td class="py-2.5 pr-4 font-semibold text-brand-400">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="py-2.5 pr-4">
                                <x-badge status="{{ $trx->status === 'completed' ? 'in-stock' : ($trx->status === 'cancelled' ? 'out' : 'low-stock') }}">
                                    {{ ucfirst($trx->status) }}
                                </x-badge>
                            </td>
                            <td class="py-2.5 pr-4 text-slate-400 text-xs">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2.5 text-right">
                                <a href="{{ route('transactions.show', $trx) }}" class="text-brand-400 hover:text-brand-300 text-sm transition">
                                    <i class="fa-solid fa-eye mr-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 6 : 5 }}" class="py-12 text-center text-slate-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div>{{ $transactions->links() }}</div>
</div>
@endsection
