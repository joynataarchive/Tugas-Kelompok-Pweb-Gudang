<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSupplier = $user->hasRole('Supplier');
        $supplierId = $user->supplier_id;

        // -----------------------------------------------------------
        // Supplier: hanya lihat data milik supplier_id akun sendiri.
        // Handle null supplier_id agar tidak crash.
        // -----------------------------------------------------------
        if ($isSupplier) {
            if (!$supplierId) {
                // Akun supplier belum terhubung ke entitas supplier — empty state
                return view('dashboard.index', [
                    'totalProducts'   => 0,
                    'totalSuppliers'  => 0,
                    'totalStockValue' => 0,
                    'lowStockProducts'=> collect(),
                    'recentMutations' => collect(),
                    'chartLabels'     => json_encode([]),
                    'chartIn'         => json_encode([]),
                    'chartOut'        => json_encode([]),
                    'isSupplier'      => true,
                    'supplierLinked'  => false,
                ]);
            }

            // Filter produk berdasarkan supplier_id
            $productQuery = Product::where('supplier_id', $supplierId);

            $totalProducts   = $productQuery->count();
            $totalSuppliers  = 1; // Supplier hanya mewakili dirinya sendiri
            $totalStockValue = $productQuery->sum(DB::raw('stock * price'));

            $lowStockProducts = $productQuery->clone()
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->with('category')
                ->get();

            // Ambil IDs produk supplier ini untuk filter mutasi
            $productIds = $productQuery->clone()->pluck('id');

            $recentMutations = StockMutation::with(['product', 'user'])
                ->whereIn('product_id', $productIds)
                ->latest()
                ->take(5)
                ->get();

            $chartData = $this->buildChartData(
                StockMutation::whereIn('product_id', $productIds)
            );

        } else {
            // Super Admin & Staff Gudang: lihat semua data global
            $totalProducts   = Product::count();
            $totalSuppliers  = Supplier::count();
            $totalStockValue = Product::sum(DB::raw('stock * price'));

            $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
                ->with('category')
                ->get();

            $recentMutations = StockMutation::with(['product', 'user'])
                ->latest()
                ->take(5)
                ->get();

            $chartData = $this->buildChartData(StockMutation::query());
        }

        return view('dashboard.index', [
            'totalProducts'   => $totalProducts,
            'totalSuppliers'  => $totalSuppliers,
            'totalStockValue' => $totalStockValue,
            'lowStockProducts'=> $lowStockProducts,
            'recentMutations' => $recentMutations,
            'chartLabels'     => $chartData['labels'],
            'chartIn'         => $chartData['in'],
            'chartOut'        => $chartData['out'],
            'isSupplier'      => $isSupplier,
            'supplierLinked'  => true,
        ]);
    }

    /**
     * Build Chart.js data: volume mutasi masuk/keluar per hari selama 7 hari terakhir.
     * Menerima query builder sehingga bisa difilter untuk Supplier.
     */
    private function buildChartData($query): array
    {
        $rows = (clone $query)
            ->selectRaw("DATE(created_at) as date, type, SUM(quantity) as total")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupByRaw("DATE(created_at), type")
            ->get()
            ->groupBy('date');

        $labels = [];
        $in     = [];
        $out    = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->translatedFormat('d M');
            $labels[] = $label;

            $day = $rows->get($date, collect());
            $in[]  = (int) optional($day->firstWhere('type', 'in'))->total;
            $out[] = (int) optional($day->firstWhere('type', 'out'))->total;
        }

        return [
            'labels' => json_encode($labels),
            'in'     => json_encode($in),
            'out'    => json_encode($out),
        ];
    }
}
