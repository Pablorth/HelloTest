<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin);
    }

    public function test_can_create_profile()
    {
        Storage::fake('public');

        $profileData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'image' => UploadedFile::fake()->image('profile.jpg'),
            'statut' => 'actif'
        ];

        $response = $this->postJson('/api/profiles', $profileData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nom' => 'Doe',
                     'prenom' => 'John',
                     'statut' => 'actif'
                 ]);

        $this->assertDatabaseHas('profiles', [
            'nom' => 'Doe',
            'prenom' => 'John'
        ]);

        Storage::disk('public')->assertExists($response->json('image'));

    }

    public function test_can_list_active_profiles()
    {
        Profile::factory()->count(3)->create(['statut' => 'actif']);
        Profile::factory()->create(['statut' => 'inactif']);

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nom', 'prenom', 'image']
                 ]);
    }

    public function test_can_update_profile()
    {
        $profile = Profile::factory()->create();
        Storage::fake('public');

        $updateData = [
            'nom' => 'Updated Name',
            'image' => UploadedFile::fake()->image('new_profile.jpg')
        ];

        $response = $this->putJson("/api/profiles/{$profile->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nom' => 'Updated Name']);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'nom' => 'Updated Name'
        ]);

        Storage::disk('public')->assertExists($response->json('image'));
    }

    public function test_can_delete_profile()
    {
        $profile = Profile::factory()->create();

        $response = $this->deleteJson("/api/profiles/{$profile->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }
}
