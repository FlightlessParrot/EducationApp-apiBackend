<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_can_get_categories(): void
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $test=Test::has('questions')->firstOrFail();
        $response = $this->actingAs($user)->get("/tests/".$test->id.'/categories');
        
        $response->assertStatus(200)->assertJsonIsObject()->assertJsonStructure(['categories']);
    }
    public function test_user_can_get_undercategories(): void
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $test=Test::has('questions')->firstOrFail();
        $response = $this->actingAs($user)->get("/tests/".$test->id.'/undercategories');
        
        $response->assertStatus(200)->assertJsonIsObject()->assertJsonStructure(['undercategories']);
    }
}
