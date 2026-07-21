@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Master Kendaraan</h1>
        <a href="{{ route('vehicles.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Tambah Kendaraan
        </a>
    </div>

    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="rounded-xl {{ $type==='success'?'bg-emerald-500/15 border-emerald-500/30 text-emerald-300':'bg-red-500/15 border-red-500/30 text-red-300' }} border px-4 py-3 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <x-card>
        <form method="GET" action="{{ route('vehicles.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau plat nomor..."
                class="flex-1 rounded-xl bg-slate-800/60 border border-white/10 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/60">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-700/60 border border-white/10 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600/60 transition">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-400 hover:text-slate-200 transition"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>

        <table class="w-full text-left text-sm text-slate-300">
            <thead>
                <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                    <th class="py-2 pr-4">#</th>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Plat Nomor</th>
                    <th class="py-2 pr-4">Tipe</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($vehicles as $i => $v)
                    <tr class="hover:bg-white/5 transition">
                        <td class="py-2.5 pr-4 text-slate-500">{{ $vehicles->firstItem() + $i }}</td>
                        <td class="py-2.5 pr-4 font-medium text-slate-100">{{ $v->name }}</td>
                        <td class="py-2.5 pr-4 font-mono text-brand-300">{{ $v->plate_number }}</td>
                        <td class="py-2.5 pr-4">{{ $v->type }}</td>
                        <td class="py-2.5 pr-4">
                            @if($v->status === 'available')
                                <x-badge status="in-stock">Tersedia</x-badge>
                            @elseif($v->status === 'borrowed')
                                <x-badge status="low-stock">Dipinjam</x-badge>
                            @else
                                <x-badge status="neutral">Perbaikan</x-badge>
                            @endif
                        </td>
                        <td class="py-2.5 text-right space-x-3">
                            <a href="{{ route('vehicles.edit', $v) }}" class="text-brand-400 hover:text-brand-300 text-sm transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                            </a>
                            <form action="{{ route('vehicles.destroy', $v) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus kendaraan {{ $v->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-12 text-center text-slate-500">Belum ada data kendaraan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
    <div>{{ $vehicles->links() }}</div>
</div>
@endsection
