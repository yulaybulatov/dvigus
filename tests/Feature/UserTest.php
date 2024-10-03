<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'login' => 'testuser',
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201);
    }
}
