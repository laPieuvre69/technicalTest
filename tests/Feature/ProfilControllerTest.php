<?php

namespace Tests\Feature;

use App\Models\Profil;
use App\Models\Administrateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProfilControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test storing a new profile
    public function test_store_profile()
    {
        Sanctum::actingAs(Administrateur::factory()->create(), ['*']);  // Authenticate user

        // Simulate file upload
        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->postJson('/api/profils', [
            'nom' => 'John Doe',
            'prénom' => 'John',
            'image' => $file,
            'statut' => 'actif',
        ]);

        $response->assertStatus(201); // Assert created
        $this->assertDatabaseHas('profils', [
            'nom' => 'John Doe',
            'prénom' => 'John',
        ]);

        // Assert that the file was uploaded to the correct location
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    // Test updating a profile
    public function test_update_profile()
    {
        Sanctum::actingAs(Administrateur::factory()->create(), ['*']);  // Authenticate user

        // Create a profile
        $profil = Profil::factory()->create([
            'nom' => 'Jane Doe',
            'prénom' => 'Jane',
            'statut' => 'inactif',
            'image' => 'fake_image_path.jpg',
        ]);

        $response = $this->putJson("/api/profils/{$profil->id}", [
            'nom' => 'Jane Updated',
            'prénom' => 'Jane Updated',
            'statut' => 'actif',
        ]);

        $response->assertStatus(200); // Assert OK
        $this->assertDatabaseHas('profils', [
            'id' => $profil->id,
            'nom' => 'Jane Updated',
            'prénom' => 'Jane Updated',
        ]);
    }

    // Test deleting a profile
    public function test_destroy_profile()
    {
        // Authenticate as a user
        Sanctum::actingAs(\App\Models\Administrateur::factory()->create(), ['*']);

        // Create a profil to destroy
        $profil = Profil::factory()->create([
            'nom' => 'John Doe',
            'prénom' => 'John',
            'statut' => 'actif',
        ]);

        // Send DELETE request to destroy the profile
        $response = $this->deleteJson("/api/profils/{$profil->id}");

        // Assert that the profile was successfully deleted
        $response->assertStatus(204);
        $this->assertDatabaseMissing('profils', ['id' => $profil->id]);
    }


    // Test list profiles (index)
    public function test_index_profiles()
    {
        // Create some profiles with a fake image
        Profil::factory()->create([
            'nom' => 'Profile 1',
            'prénom' => 'Test 1',
            'statut' => 'actif',
            'image' => 'fake_image_path.jpg',
        ]);
        Profil::factory()->create([
            'nom' => 'Profile 2',
            'prénom' => 'Test 2',
            'statut' => 'actif',
            'image' => 'fake_image_path.jpg',
        ]);

        $response = $this->getJson('/api/profils');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    // Test authentication (unauthorized user)
    public function test_unauthorized_user_store_profile()
    {
        $response = $this->postJson('/api/profils', [
            'nom' => 'Unauthorized User',
            'prénom' => 'Test',
            'statut' => 'actif',
        ]);

        $response->assertStatus(401); // Unauthorized
    }
}
