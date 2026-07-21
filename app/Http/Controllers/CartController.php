<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    public function index()
    {
        $cart = $this->getOrCreateCart()->load('items.product');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart    = $this->getOrCreateCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $totalQty = ($item?->quantity ?? 0) + $request->quantity;

        if ($totalQty > $product->stock) {
            return back()->with('error', "Stok {$product->name} tidak mencukupi (stok: {$product->stock}).");
        }

        if ($item) {
            $item->update(['quantity' => $totalQty]);
        } else {
            CartItem::create([
                'cart_id'        => $cart->id,
                'product_id'     => $product->id,
                'quantity'       => $request->quantity,
                'price_snapshot' => $product->price,
            ]);
        }

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);
        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);
        $cartItem->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->getOrCreateCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Validasi stok semua item
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "Stok {$item->product->name} tidak mencukupi.");
            }
        }

        DB::transaction(function () use ($cart) {
            $total = $cart->items->sum(fn($i) => $i->price_snapshot * $i->quantity);

            $transaction = Transaction::create([
                'user_id'      => auth()->id(),
                'total_amount' => $total,
                'status'       => 'completed',
            ]);

            foreach ($cart->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item->product_id,
                    'quantity'       => $item->quantity,
                    'price'          => $item->price_snapshot,
                ]);

                // Kurangi stok & catat mutasi keluar
                $product = $item->product;
                $stockBefore = $product->stock;
                $product->decrement('stock', $item->quantity);

                StockMutation::create([
                    'product_id'   => $product->id,
                    'user_id'      => auth()->id(),
                    'type'         => 'out',
                    'quantity'     => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after'  => $product->fresh()->stock,
                    'note'         => "Checkout transaksi #{$transaction->id}",
                ]);
            }

            // Kosongkan keranjang
            $cart->items()->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Checkout berhasil! Transaksi telah dibuat.');
    }

    private function authorizeCartItem(CartItem $item): void
    {
        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }
    }
}
