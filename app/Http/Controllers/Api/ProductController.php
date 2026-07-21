<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

/**
 * API CRUD untuk produk.
 * Menggunakan StoreProductRequest & UpdateProductRequest dari Role 2 karena
 * validasinya sudah lengkap (sku unik, foreign key category_id & supplier_id)
 * dan sama persis dengan yang dibutuhkan di API.
 * Semua endpoint dilindungi auth:sanctum (lihat routes/api.php).
 */
class ProductController extends Controller
{
    /**
     * GET /api/products — Daftar produk (paginasi 10).
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'supplier'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar produk berhasil diambil.',
            'data'    => $products,
        ]);
    }

    /**
     * POST /api/products — Tambah produk baru.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil ditambahkan.',
            'data'    => $product->load(['category', 'supplier']),
        ], 201);
    }

    /**
     * GET /api/products/{product} — Detail produk.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'Detail produk berhasil diambil.',
            'data'    => $product->load(['category', 'supplier']),
        ]);
    }

    /**
     * PUT/PATCH /api/products/{product} — Perbarui produk.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil diperbarui.',
            'data'    => $product->fresh(['category', 'supplier']),
        ]);
    }

    /**
     * DELETE /api/products/{product} — Hapus produk.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil dihapus.',
            'data'    => null,
        ]);
    }
}
