<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed 3 kategori, 2 supplier, 4 produk contoh.
     * Salah satu produk sengaja dibuat stok < minimum_stock untuk testing fitur "stok rendah".
     */
    public function run(): void
    {
        $elektronik = Category::firstOrCreate(
            ['slug' => 'elektronik'],
            ['name' => 'Elektronik', 'description' => 'Perangkat & aksesoris elektronik']
        );
        $atk = Category::firstOrCreate(
            ['slug' => 'alat-tulis-kantor'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'Kebutuhan tulis-menulis kantor']
        );
        $gudang = Category::firstOrCreate(
            ['slug' => 'perlengkapan-gudang'],
            ['name' => 'Perlengkapan Gudang', 'description' => 'Alat bantu operasional gudang']
        );

        $globalTech = Supplier::firstOrCreate(
            ['name' => 'PT. Global Technology Indonesia'],
            ['contact_person' => 'Budi Santoso', 'phone' => '021-5550101', 'email' => 'contact@globaltech.co.id', 'address' => 'Jakarta']
        );
        $sumberMakmur = Supplier::firstOrCreate(
            ['name' => 'CV Sumber Makmur'],
            ['contact_person' => 'Siti Aminah', 'phone' => '021-5550202', 'email' => 'info@sumbermakmur.co.id', 'address' => 'Bandung']
        );

        Product::firstOrCreate(['sku' => 'ELK-001'], [
            'name' => 'Kabel HDMI 2 Meter',
            'category_id' => $elektronik->id,
            'supplier_id' => $globalTech->id,
            'unit' => 'pcs',
            'price' => 45000,
            'cost_price' => 30000,
            'stock' => 50,
            'minimum_stock' => 10,
        ]);

        Product::firstOrCreate(['sku' => 'ELK-002'], [
            'name' => 'Power Bank 10000mAh',
            'category_id' => $elektronik->id,
            'supplier_id' => $globalTech->id,
            'unit' => 'pcs',
            'price' => 150000,
            'cost_price' => 110000,
            'stock' => 8, // sengaja < minimum_stock
            'minimum_stock' => 10,
        ]);

        Product::firstOrCreate(['sku' => 'ATK-001'], [
            'name' => 'Kertas A4 80gsm',
            'category_id' => $atk->id,
            'supplier_id' => $sumberMakmur->id,
            'unit' => 'rim',
            'price' => 52000,
            'cost_price' => 42000,
            'stock' => 120,
            'minimum_stock' => 20,
        ]);

        Product::firstOrCreate(['sku' => 'GDG-001'], [
            'name' => 'Rak Besi Susun 4 Tingkat',
            'category_id' => $gudang->id,
            'supplier_id' => $sumberMakmur->id,
            'unit' => 'unit',
            'price' => 850000,
            'cost_price' => 700000,
            'stock' => 5,
            'minimum_stock' => 5,
        ]);
    }
}
