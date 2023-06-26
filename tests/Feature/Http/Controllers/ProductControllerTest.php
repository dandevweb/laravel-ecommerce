<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    public function test_can_create_product_when_optional_fields_are_not_filled()
    {
        $response = $this->actingAs($this->superAdminUser())->post(
            route('admin.products.store'),
            [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100,
                'category_id' => Category::factory()->create()->id,
                'track_quantity' => 1,
                'quantity' => 10,
                'sell_out_of_stock' => 1,
                'status' => 'active',
                'sku' => Str::random(10),
                'cost' => '',
                'discounted_price' => '',
            ],
        );

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));

        $this->assertCount(1, Product::all());

        $product = Product::first();
        $this->assertEquals(0, $product->cost);
        $this->assertEquals(0, $product->discounted_price);

        $response = $this->actingAs($this->superAdminUser())->post(
            route('admin.products.store'),
            [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100,
                'category_id' => Category::factory()->create()->id,
                'track_quantity' => 0,
                'quantity' => null,
                'sell_out_of_stock' => 0,
                'cost' => 10,
                'status' => 'active',
                'sku' => Str::random(10),
            ],
        );

        $response->assertRedirect(route('admin.products.index'));
        $this->assertCount(2, Product::all());

        $product = Product::find(2);
        $this->assertEquals(10, $product->cost);
        $this->assertEquals(0, $product->discounted_price);
        $this->assertEquals(0, $product->quantity);
    }
}
