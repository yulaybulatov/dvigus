<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_new_user()
    {
        $response = $this->postJson('/api/register', [
            'login' => 'example',
            'name' => 'Name',
            'email' => 'example@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['user' => ['id', 'login', 'name', 'email']]);

        $this->assertDatabaseHas('users', [
            'login' => 'example',
            'email' => 'example@example.com',
        ]);
    }

    /** @test */
    public function it_fails_if_login_or_email_already_exists()
    {
        // Создаем пользователя
        User::factory()->create([
            'login' => 'example',
            'name' => 'Name',
            'email' => 'example@example.com',
        ]);

        // Попытка регистрации с уже существующими данными
        $response = $this->postJson('/api/register', [
            'login' => 'example',
            'name' => 'Name',
            'email' => 'example@example.com',
            'password' => 'test_password123',
        ]);

        $response->assertStatus(409); // Пользователь уже существует
    }
}
