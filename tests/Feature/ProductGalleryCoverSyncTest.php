<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductGalleryCoverSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_first_uploaded_image_becomes_the_products_cover(): void
    {
        $product = Product::factory()->create(['image' => null]);

        ProductImage::create([
            'product_id' => $product->id,
            'image' => 'first.jpg',
            'is_cover' => true,
            'sort_order' => 0,
        ]);

        $this->assertSame('first.jpg', $product->fresh()->image);
    }

    public function test_marking_a_different_image_as_cover_updates_the_product_and_unmarks_the_old_one(): void
    {
        $product = Product::factory()->create(['image' => null]);

        $first = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'first.jpg',
            'is_cover' => true,
            'sort_order' => 0,
        ]);

        $second = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'second.jpg',
            'is_cover' => false,
            'sort_order' => 1,
        ]);

        $second->update(['is_cover' => true]);

        $this->assertSame('second.jpg', $product->fresh()->image);
        $this->assertFalse($first->fresh()->is_cover);
    }

    public function test_deleting_the_cover_image_falls_back_to_the_next_remaining_image(): void
    {
        $product = Product::factory()->create(['image' => null]);

        $first = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'first.jpg',
            'is_cover' => true,
            'sort_order' => 0,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image' => 'second.jpg',
            'is_cover' => false,
            'sort_order' => 1,
        ]);

        $first->delete();

        $this->assertSame('second.jpg', $product->fresh()->image);
    }

    public function test_deleting_the_only_image_clears_the_products_cover(): void
    {
        $product = Product::factory()->create(['image' => null]);

        $only = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'only.jpg',
            'is_cover' => true,
            'sort_order' => 0,
        ]);

        $only->delete();

        $this->assertNull($product->fresh()->image);
    }
}
