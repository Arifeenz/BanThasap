<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeHighlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_at_most_three_highlighted_products(): void
    {
        Product::factory()->count(5)->create(['is_active' => true]);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertLessThanOrEqual(3, $response->viewData('products')->count());
    }

    public function test_featured_products_are_prioritized_on_the_homepage(): void
    {
        Product::factory()->create([
            'is_active' => true,
            'is_featured' => true,
            'name' => 'Featured Product',
        ]);

        Product::factory()->count(4)->create([
            'is_active' => true,
            'is_featured' => false,
        ]);

        $response = $this->get('/');

        $names = $response->viewData('products')->pluck('name');
        $this->assertTrue($names->contains('Featured Product'));
    }

    public function test_inactive_products_never_appear_on_the_homepage(): void
    {
        Product::factory()->create([
            'is_active' => false,
            'name' => 'Hidden Product',
        ]);

        $response = $this->get('/');

        $names = $response->viewData('products')->pluck('name');
        $this->assertFalse($names->contains('Hidden Product'));
    }

    public function test_homepage_does_not_error_when_there_are_no_products(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $this->assertCount(0, $response->viewData('products'));
    }
}
