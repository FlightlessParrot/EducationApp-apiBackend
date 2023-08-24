<?php

namespace Tests\Feature;

use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GeneratedTestControllerTest extends TestCase
{
    use RefreshDatabase;
    private $data=[
        'egzam'=>'false',
        
        'time'=>'00:50',
        'questionsOneAnswer'=>'one-answer',
        'questionsManyAnswers'=>'many-answer',
        'questionsPairs'=>'pairs',
        'questionsOrder'=>'order'
    ];
    public function test_user_can_generate_test(): void
    {
        $this->seed();
    
        $user=User::where('email','test@example.com')->first();
        $test=$user->tests()->where('role','custom')->firstOrFail();
        
        $response = $this->actingAs($user)->postJson('/generate-test',[...$this->data,'test_id'=>$test->id,]);
   
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json)=>
            $json->has('test')
        );
        $test=GeneratedTest::find($response['test']);
        $generatedQuestions=$test->generatedQuestions()->get();
        $this->assertModelExists($test);
        $this->assertCount(10, $generatedQuestions);
    }
    public function test_user_cannot_generate_unowned_test()
    {
        $test=\App\Models\Test::factory()->has(User::factory()->state([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>Hash::make('TestPassword'),
           
       ]))->create();
       for($i=0;$i<160;$i++)
       {
        $question=\App\Models\Question::factory()->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        $user=User::factory()->create();
        $response = $this->actingAs($user)->postJson('/generate-test',[
            ...$this->data,
            'egzam'=>'false',
            'test_id'=>'g'.$test->id,
            'time'=>'00:50',
            'questions_number'=>'150'
        ]);

        $response->assertNotFound();
    } 
    public function test_questions_not_double()
    {
        $test=\App\Models\Test::factory()->has(User::factory()->state([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>Hash::make('TestPassword'),
           
       ]))->create();
       for($i=0;$i<10;$i++)
       {
        $question=\App\Models\Question::factory()->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        $user=User::where('name', 'Test User')->firstOrFail();
        $response = $this->actingAs($user)->postJson('/generate-test',[
            ...$this->data,
            'egzam'=>'false',
            'test_id'=>$test->id,
            'time'=>'00:50',
            'questions_number'=>'150'
        ]);
        $response->assertSuccessful();
        $genTest=$user->generatedTests()->first();
        $questions=$genTest->generatedQuestions->all();

        $this->assertCount(10,$questions);

    }
    public function test_user_can_view_test()
    {
        $this->seed();
    
        $user=User::where('email','test@example.com')->first();
        $generatedTest=$user->generatedTests()->firstOrFail();
       
        $response=$this->actingAs($user)->get('/generated-tests/'.$generatedTest->id.'/view');

        $response->assertSuccessful()->assertJsonCount($generatedTest->questions_number,'qas');
    }
    public function test_user_can_get_answers_data()
    {
        $this->seed();
    
        $user=User::where('email','test@example.com')->first();
        $generatedTest=$user->generatedTests()->firstOrFail();

        $response=$this->actingAs($user)->get('/generated-tests/'.$generatedTest->id.'/answers');
        
        $response->assertSuccessful()->assertJsonIsObject('correct')->assertJsonCount($generatedTest->questions_number,'correct');
    }
    public function test_user_can_get_delete_generated_test()
    {
        $this->seed();
    
        $user=User::where('email','test@example.com')->first();
        $generatedTest=$user->generatedTests()->firstOrFail();

        $response=$this->actingAs($user)->delete('/generated-tests/'.$generatedTest->id);

        $response->assertNoContent();
        $this->assertModelMissing($generatedTest);
    }

}
