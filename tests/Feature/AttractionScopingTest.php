<?php

namespace Tests\Feature;

use App\Models\Attraction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttractionScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_only_sees_attractions_they_created(): void
    {
        Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $otherAdmin = User::factory()->create();
        $otherAdmin->assignRole('admin');

        Attraction::factory()->create([
            'name' => 'Own Attraction',
            'created_by' => $admin->id,
        ]);

        Attraction::factory()->create([
            'name' => 'Others Attraction',
            'created_by' => $otherAdmin->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/attractions');

        $response->assertOk();
        $response->assertSee('Own Attraction');
        $response->assertDontSee('Others Attraction');
    }

    public function test_super_admin_sees_attractions_created_by_everyone(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'super_admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        Attraction::factory()->create([
            'name' => 'Admin Owned Attraction',
            'created_by' => $admin->id,
        ]);

        Attraction::factory()->create([
            'name' => 'Super Admin Owned Attraction',
            'created_by' => $superAdmin->id,
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin/attractions');

        $response->assertOk();
        $response->assertSee('Admin Owned Attraction');
        $response->assertSee('Super Admin Owned Attraction');
    }
}
