<?php

namespace Tests\Unit;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register()
    {
        $adminData = [
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/register', $adminData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('admins', [
            'email' => 'admin@test.com'
        ]);
    }

    public function test_admin_can_login()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123')
        ]);

        $loginData = [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}
