<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Staff Gudang']);

        $transactions = Transaction::with('user')
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', "%{$request->search}%")
            ))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'isAdmin'));
    }

    public function show(Transaction $transaction)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'Staff Gudang']) && $transaction->user_id !== $user->id) {
            abort(403);
        }

        $transaction->load('items.product', 'user');
        return view('transactions.show', compact('transaction'));
    }
}
