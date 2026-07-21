@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-100">Kelola Roles & Permissions</h1>
        <a href="{{ route('roles.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition">
            <i class="fa-solid fa-plus"></i> Tambah Role
        </a>
    </div>

    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="rounded-xl {{ $type==='success'?'bg-emerald-500/15 border-emerald-500/30 text-emerald-300':'bg-red-500/15 border-red-500/30 text-red-300' }} border px-4 py-3 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="glass-card text-xs text-amber-300 border border-amber-500/20 bg-amber-500/5">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
        Role default <strong>Super Admin</strong>, <strong>Staff Gudang</strong>, dan <strong>Supplier</strong> tidak dapat diedit atau dihapus.
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="py-2 pr-4">#</th>
                        <th class="py-2 pr-4">Nama Role</th>
                        <th class="py-2 pr-4">Jumlah Permission</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($roles as $i => $role)
                        @php $isProtected = in_array($role->name, ['Super Admin', 'Staff Gudang', 'Supplier']); @endphp
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-2.5 pr-4 text-slate-500">{{ $roles->firstItem() + $i }}</td>
                            <td class="py-2.5 pr-4 font-medium text-slate-100">
                                {{ $role->name }}
                                @if($isProtected)
                                    <span class="ml-2 text-xs text-amber-400"><i class="fa-solid fa-lock"></i> default</span>
                                @endif
                            </td>
                            <td class="py-2.5 pr-4">
                                <span class="rounded-full bg-slate-700/60 px-2 py-0.5 text-xs text-slate-300">
                                    {{ $role->permissions_count }} permissions
                                </span>
                            </td>
                            <td class="py-2.5 text-right space-x-3">
                                @if(!$isProtected)
                                    <a href="{{ route('roles.edit', $role) }}" class="text-brand-400 hover:text-brand-300 text-sm transition">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">
                                            <i class="fa-solid fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-600">Terlindungi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-10 text-center text-slate-500">Belum ada role.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div>{{ $roles->links() }}</div>
</div>
@endsection
