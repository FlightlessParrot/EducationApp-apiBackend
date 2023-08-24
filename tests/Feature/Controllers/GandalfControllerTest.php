<?php

namespace Tests\Feature\Controllers;

use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GandalfControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_can_create_egzam_from_generated_test(): void
    {
        $this->seed();

        $user=User::where('email','test@example.com')->first();
        $generatedTest=$user->generatedTests()->firstOrFail();
        $team=$user->teams()->firstOrFail();
        $response = $this->actingAs($user)->post('/teams/'.$team->id.'/generated-tests/'.$generatedTest->id.'/egzam/create', ['name'=>'My egzam']);
        $egzam=Test::find($response['testId']);
        $response->assertStatus(200);
        $this->assertModelExists($egzam);


    }

    public function test_user_can_delete_test(): void
    {
        $this->seed();

        $user=User::where('email','test@example.com')->first();
        $team=$user->teams()->whereRelation('tests','role', 'egzam')->firstOrFail();
        $egzam=$team->tests()->where('role', 'egzam')->firstOrFail();

        $response = $this->actingAs($user)->delete('/teams/'.$team->id.'/tests/'.$egzam->id.'/egzam/delete');
        
        $response->assertSuccessful();
        $this->assertModelMissing($egzam);
    }

    public function test_user_can_start_egzam()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $team=$user->teams()->wherePivot('is_teacher', true)->firstOrFail();
        $test=$team->tests()->where('role','egzam')->firstOrFail();
        $test->fillable=false;
        $test->save();
        $response=$this->actingAs($user)->put('/teams/'.$team->id.'/egzams/'.$test->id.'/start');
        $test->refresh();
        $response->assertOk();
        $this->assertTrue((bool)$test->fillable);
    }

    public function test_teacher_can_get_egzams()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $team=$user->teams()->wherePivot('is_teacher', true)->firstOrFail();

        $response=$this->actingAs($user)->get('/teams/'.$team->id.'/egzams/show');

        $response->assertOk();
        $response->assertJsonIsObject();
        $response->assertJsonCount(count($team->tests()->where('role','egzam')->get()),'egzams');
      
    }

}
