<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_authenticated_user_get_204(): void
    {
        $user=User::factory()->create();
        $response = $this->actingAs($user)->get('/checkAuth');

        $response->assertStatus(204);
    }
    public function test_unauthenticated_user_get_302(): void
    {
     
        $response = $this->get('/checkAuth');

        $response->assertStatus(302);
    }
}
