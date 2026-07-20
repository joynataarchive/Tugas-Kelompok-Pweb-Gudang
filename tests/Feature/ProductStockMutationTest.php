<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\StockMutation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStockMutationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Agus Gudang',
            'email' => 'staff@inventory.com',
        ]);

        $this->category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'description' => 'Perangkat elektronik',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'PT. Global Technology Indonesia',
            'contact_person' => 'Budi Santoso',
            'phone' => '021-5550101',
            'email' => 'contact@globaltech.co.id',
            'address' => 'Jakarta',
        ]);
    }

    public function test_can_view_products_index(): void
    {
        Product::create([
            'sku' => 'ELK-001',
            'name' => 'Kabel HDMI 2 Meter',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'unit' => 'pcs',
            'price' => 45000,
            'cost_price' => 30000,
            'stock' => 50,
            'minimum_stock' => 10,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Kabel HDMI 2 Meter');
        $response->assertSee('ELK-001');
    }

    public function test_can_create_product_with_validation(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'sku' => 'ELK-002',
                'name' => 'Power Bank 10000mAh',
                'category_id' => $this->category->id,
                'supplier_id' => $this->supplier->id,
                'unit' => 'pcs',
                'price' => 150000,
                'cost_price' => 110000,
                'stock' => 8,
                'minimum_stock' => 10,
            ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'ELK-002',
            'name' => 'Power Bank 10000mAh',
        ]);
    }

    public function test_validation_prevents_duplicate_sku(): void
    {
        Product::create([
            'sku' => 'ELK-001',
            'name' => 'Kabel HDMI 2 Meter',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'unit' => 'pcs',
            'price' => 45000,
            'cost_price' => 30000,
            'stock' => 50,
            'minimum_stock' => 10,
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'sku' => 'ELK-001',
                'name' => 'Kabel HDMI Baru',
                'category_id' => $this->category->id,
                'supplier_id' => $this->supplier->id,
                'unit' => 'pcs',
                'price' => 45000,
                'cost_price' => 30000,
                'stock' => 10,
                'minimum_stock' => 5,
            ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors('sku');
    }

    public function test_can_record_stock_in_mutation(): void
    {
        $product = Product::create([
            'sku' => 'ELK-001',
            'name' => 'Kabel HDMI 2 Meter',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'unit' => 'pcs',
            'price' => 45000,
            'cost_price' => 30000,
            'stock' => 50,
            'minimum_stock' => 10,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock-mutations.store'), [
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => 10,
                'note' => 'Barang masuk dari supplier',
            ]);

        $response->assertRedirect(route('stock-mutations.index'));
        $this->assertEquals(60, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_mutations', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 10,
            'stock_before' => 50,
            'stock_after' => 60,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_cannot_record_stock_out_mutation_when_stock_insufficient(): void
    {
        $product = Product::create([
            'sku' => 'ELK-001',
            'name' => 'Kabel HDMI 2 Meter',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'unit' => 'pcs',
            'price' => 45000,
            'cost_price' => 30000,
            'stock' => 5,
            'minimum_stock' => 10,
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('stock-mutations.create'))
            ->post(route('stock-mutations.store'), [
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => 10,
                'note' => 'Kirim ke customer',
            ]);

        $response->assertRedirect(route('stock-mutations.create'));
        $response->assertSessionHasErrors('quantity');
        
        $errors = session('errors')->get('quantity');
        $this->assertStringContainsString('Stok tidak mencukupi. Stok saat ini: 5', $errors[0]);

        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertDatabaseMissing('stock_mutations', [
            'product_id' => $product->id,
            'type' => 'out',
        ]);
    }
}
