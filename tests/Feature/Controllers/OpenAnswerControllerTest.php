<?php

namespace Tests\Feature\Controllers;

use App\Models\GeneratedQuestion;
use App\Models\OpenAnswer;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpenAnswerControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_can_view_open_questions(): void
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $egzam=Test::where('role', 'egzam')->first();
        $response = $this->actingAs($user)->get('egzams/'.$egzam->id.'/open-question');

        $response->assertStatus(200);
        $response->assertJsonIsObject();
    }
    public function test_user_can_give_grade(): void
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $generatedQuestion=GeneratedQuestion::where('relevant', false)->first();
     
        $openAnswer=$generatedQuestion->openAnswer()->first();
        $response = $this->actingAs($user)->put('open-answers/'.$openAnswer->id.'/grade',['grade'=>'good']);
        $generatedQuestion->refresh();
        $response->assertStatus(204);
        $this->assertEquals(1,$generatedQuestion->relevant);
        $this->assertEquals(1,$generatedQuestion->answer);
    }
    public function test_notyfication_is_removed()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $generatedQuestions=GeneratedQuestion::where('relevant', false)->get();
        $notyficaton=$generatedQuestions[0]->generatedTest()->first()->test()->first()->notyfications()->where('type','openQuestionToCheck')->firstOrFail();
        foreach($generatedQuestions as $generatedQuestion)
        {
            $openAnswer=$generatedQuestion->openAnswer()->first();
            $response = $this->actingAs($user)->put('open-answers/'.$openAnswer->id.'/grade',['grade'=>'good']);
        }
        $response->assertStatus(204);
        $this->assertModelMissing($notyficaton);
    }
}
