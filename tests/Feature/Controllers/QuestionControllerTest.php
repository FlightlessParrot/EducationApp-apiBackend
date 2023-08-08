<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_can_attach_questions(): void
    {
       $this->seed();
       $user=User::has('tests')->first();
       $question=$user->tests()->first()->questions()->first();
       $test=Test::factory()->create(['custom'=>true]);
       $user->tests()->attach($test);

       $response=$this->actingAs($user)->post('/tests/'.$test->id.'/questions/'.$question->id.'/attach');

    $response->assertSuccessful();
    $this->assertModelExists($test->questions()->first());

    }
    public function test_user_can_detach_questions(): void
    {
       $this->seed();
       $user=User::has('tests')->first();
       $test=$user->tests()->where('custom',true)->first();
       $question=$test->questions()->first();
       $answer=$question->answers()->first();
       $question->custom=true;
       $question->save();
       $question->fresh();
       $user->tests()->attach($test);

       $response=$this->actingAs($user)->delete('/tests/'.$test->id.'/questions/'.$question->id.'/detach');

    $response->assertSuccessful();
    $this->assertModelMissing($question);
    $this->assertModelMissing($answer);
    }
    public function test_user_can_destroy_questions(): void
    {
       $this->seed();
       $user=User::has('tests')->first();
       $test=$user->tests()->where('custom',true)->first();
       $question=$test->questions()->first();
       $question->custom=false;
       $question->save();
       $question->fresh();
       $user->tests()->attach($test);

       $response=$this->actingAs($user)->delete('/tests/'.$test->id.'/questions/'.$question->id.'/detach');

    $response->assertSuccessful();
    $this->assertNull($question->tests()->first());
    }

    public function test_user_can_find_question()
    {
      $this->seed();
      $user=User::has('tests')->first();
      $question=$user->tests()->first()->questions()->firstOrFail();
      $response=$this->actingAs($user)->post('/questions/find',['search'=>$question->question]);
      $response->assertSuccessful();
      $response->assertJsonPath('0.question', $question->question);

    }

    public function test_user_can_find_owned_question()
    {
      $this->seed();
      $user=User::has('tests')->first();
      $test=$user->tests()->first();
      $question=$test->questions()->firstOrFail();
      $response=$this->actingAs($user)->post('/tests/'.$test->id.'/questions/owned',['search'=>$question->question]);
      $response->assertSuccessful();
      $response->assertJsonPath('0.question', $question->question);
    }
    public function test_user_can_find_unowned_question()
    {
      $this->seed();
      $user=User::has('tests')->first();
      $test=$user->tests()->first();
      $question=$test->questions()->firstOrFail();

      $otherTest=Test::factory()->has(Question::factory()->state(['question'=>$question->question]))->create();
      $otherTest->users()->attach($user->id);
      
      $response=$this->actingAs($user)->post('/tests/'.$otherTest->id.'/questions/unowned',['search'=>$question->question]);
      $response->assertSuccessful();
      $response->assertJsonPath('0.question', $question->question)->assertJsonCount(1);
      
    }
}
