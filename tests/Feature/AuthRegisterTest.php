<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_errors_for_all_missing_fields(): void
    {
        $response = $this->postJson('/api/v1/register');

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_creates_a_user(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Geovany',
            'email' => 'geovany@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.name', 'Geovany')
            ->assertJsonPath('user.email', 'geovany@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'geovany@example.com',
        ]);
    }
}
