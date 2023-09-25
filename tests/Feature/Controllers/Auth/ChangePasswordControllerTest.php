<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_change_password(): void
    {
        $user=\app\Models\User::factory()->create();
        $response = $this->actingAs($user)->put('user/password/update',['password'=>'12345678','password_confirmation'=>'12345678']);
        $user->refresh();
        $response->assertStatus(204);
        $this->assertTrue(password_verify('12345678',$user->password));
    }
}
