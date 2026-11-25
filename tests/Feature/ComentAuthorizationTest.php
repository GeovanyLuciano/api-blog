<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_coments(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, "sanctum")
            ->getJson("/api/v1/comentarios")
            ->assertOk();
    }
}
