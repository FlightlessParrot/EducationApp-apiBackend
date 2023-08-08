<?php

namespace Tests\Feature\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TestControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_if_user_can_find_test(): void
    {
        $user=User::factory()->has(Test::factory()->state(['name'=>'123 Test 123']))->create();
        $response = $this->actingAs($user)->post('/tests/find',['search'=>'Test','custom'=>false]);

        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $response->assertJsonPath('0.name','123 Test 123');

    }
    public function test_user_can_create_test(): void
    {
       
        $this->seed();
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $test=$user->tests()->firstOrFail();
        $response = $this->actingAs($user)->post('/tests/create', [
            'name' => 'test name',
            'test_id' => $test->id
        ]);
        $response->assertStatus(204);
        $createdTest=$user->tests()->where('name', 'test name')->first();
        $this->assertModelExists($createdTest);
        $this->assertDatabaseHas('tests', ['name' => 'test name']);
        $this->assertInstanceOf(Question::class, $createdTest->questions()->first());
        
    }
    public function test_validation_works_during_test_creation()
    {
        $this->seed();
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $name = function () {
            $a = '';
            for ($i = 0; $i < 290; $i++) {
                $a .= 'n';
            }
            return $a;
        };
        $response = $this->actingAs($user)->post('/tests/create', [
            'name' => $name(),
            'test_id' => ' abx '
        ]);

        $response->assertInvalid(['name'=>'The name field must not be greater than 250 characters.','test_id'=>"The test id field must be an integer."]);
    }
    
    public function test_user_can_delete_custom_test()
    {
        $test=Test::factory()->has(User::factory())->create(['custom'=>true]);
        
        $response=$this->actingAs($test->users()->firstOrFail())->delete('/tests/'.$test->id."/delete");
        $response->assertNoContent();
        $this->assertModelMissing($test);
    }

    public function test_user_can_remove_all_questions()
    {
        $this->seed();
        $test=Test::where('custom', true)->firstOrFail();
        $user=$test->users()->first();
        $response=$this->actingAs($user)->delete('/tests/'.$test->id.'/questions/remove');
        
        $response->assertSuccessful();
        $this->assertDatabaseMissing('question_test',['test_id'=> $test->id]);
    }
}
