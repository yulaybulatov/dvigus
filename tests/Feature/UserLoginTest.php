<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_logs_in_a_user()
    {
        $user = User::factory()->create([
            'login' => 'example',
            'email' => 'example@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'example',
            'email' => 'example@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    /** @test */
    public function it_fails_with_invalid_credentials()
    {
        // Тест, если пользователь вводит неверные данные
        $response = $this->postJson('/api/login', [
            'login' => 'invaliduser',
            'email' => 'invaliduser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials']);
    }
}
