<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMutationRequest;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMutationController extends Controller
{
    public function index()
    {
        $mutations = StockMutation::with(['product', 'user'])
            ->latest()
            ->paginate(10);

        return view('stock-mutations.index', compact('mutations'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('stock-mutations.form', compact('products'));
    }

    public function store(StoreStockMutationRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            // lockForUpdate: cegah race condition kalau 2 staff mencatat mutasi
            // produk yang sama secara bersamaan.
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            $stockBefore = $product->stock;

            if ($validated['type'] === 'out' && $validated['quantity'] > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => ["Stok tidak mencukupi. Stok saat ini: {$product->stock}"],
                ]);
            }

            $product->stock += $validated['type'] === 'in'
                ? $validated['quantity']
                : -$validated['quantity'];
            $product->save();

            StockMutation::create([
                'product_id' => $product->id,
                'user_id' => $request->user()?->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $product->stock,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return redirect()->route('stock-mutations.index')->with('success', 'Mutasi stok berhasil dicatat.');
    }
}
