<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequestRequest;
use App\Models\ItemRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemRequestController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Staff Gudang']);

        $query = ItemRequest::with(['product', 'user', 'verifiedBy'])
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->when($request->search, fn($q) => $q->whereHas('product', fn($p) =>
                $p->where('name', 'like', "%{$request->search}%")
            ))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        $itemRequests = $query->paginate(10)->withQueryString();

        return view('item-requests.index', compact('itemRequests', 'isAdmin'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('item-requests.form', compact('products'));
    }

    public function store(StoreItemRequestRequest $request)
    {
        ItemRequest::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'status'  => 'pending',
        ]);

        return redirect()->route('item-requests.index')->with('success', 'Permintaan barang berhasil diajukan.');
    }

    /**
     * Approve atau reject permintaan — hanya Super Admin & Staff Gudang.
     */
    public function verify(Request $request, ItemRequest $itemRequest)
    {
        $request->validate([
            'action' => ['required', 'in:approved,rejected'],
        ]);

        $itemRequest->update([
            'status'      => $request->action,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $label = $request->action === 'approved' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Permintaan berhasil {$label}.");
    }
}
