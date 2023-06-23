<?php

namespace Tests\Feature;

use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GeneratedTestControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_generate_test(): void
    {
        
        $test=\App\Models\Test::factory()->has(User::factory()->state([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>Hash::make('TestPassword')
       ]))->create();
       for($i=0;$i<160;$i++)
       {
        $question=\App\Models\Question::factory()->for($test)->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
    }

        $user=User::where('email'==='test@example.com');
        $test=Test::firstOrFail();
        
        $response = $this->actingAs($user)->postJson('/generate-test',[
            'egzam'=>'false',
            'test_id'=>$test->id,
            'time'=>'00:50',



        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json)=>
            $json->has('test')->has('questions')
        );




       
    }
}
