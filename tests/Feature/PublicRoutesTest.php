<?php

namespace Tests\Feature;

use App\Models\Attraction;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('publicRoutes')]
    public function test_public_route_loads_successfully(string $uri): void
    {
        $response = $this->get($uri);

        $response->assertOk();
    }

    public static function publicRoutes(): array
    {
        return [
            'home' => ['/'],
            'products list' => ['/products'],
            'attractions list' => ['/attractions'],
            'posts list' => ['/posts'],
            'villages list' => ['/villages'],
            'map' => ['/map'],
        ];
    }

    public function test_product_detail_page_loads_for_an_active_product(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->get('/products/'.$product->slug);

        $response->assertOk();
    }

    public function test_product_detail_page_is_not_found_for_an_inactive_product(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $response = $this->get('/products/'.$product->slug);

        $response->assertNotFound();
    }

    public function test_attraction_detail_page_loads_for_an_active_attraction(): void
    {
        $attraction = Attraction::factory()->create(['is_active' => true]);

        $response = $this->get('/attractions/'.$attraction->slug);

        $response->assertOk();
    }
}
