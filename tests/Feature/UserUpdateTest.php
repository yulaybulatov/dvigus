<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'oldemail@example.com',
        ]);

        $response = $this->actingAs($user)->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'User updated']);

        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);
    }

    /** @test */
    public function it_verifies_email_if_changed()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'email' => 'oldemail@example.com',
        ]);

        $response = $this->actingAs($user)->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
        ]);

        $user->refresh();

        $this->assertNotNull($user->email_verified_at);
    }
}
