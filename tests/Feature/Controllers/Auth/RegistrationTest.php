<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
       
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'adress'=>'adress',
            'postal_code'=>'81-123',
            'city'=>'Marienburg'
        ]);
     
       $this->assertAuthenticated();
        $response->assertNoContent();
    }
}
