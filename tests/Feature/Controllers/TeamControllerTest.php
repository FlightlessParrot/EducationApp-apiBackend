<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;
    public function test_user_can_create_team(): void
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $teamsNumber=count(Team::all());
        $response = $this->actingAs($user)->post('/team/create', ['name'=>fake()->sentence()]);


        $response->assertStatus(200);
        $this->assertCount($teamsNumber+1,Team::all());
    }
    public function test_only_user_can_create_team(): void
    {
        $this->seed();
        $user=User::factory()->create();
        $teamsNumber=count(Team::all());
        $response = $this->actingAs($user)->post('/team/create',['name'=>fake()->sentence()]);


        $response->assertStatus(401);
        $this->assertCount($teamsNumber,Team::all());
    }

    public function test_user_can_view_all_their_tests()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $teams=$user->teams()->wherePivot('is_teacher', true)->get();
      
        $user=User::where('email','test@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/teams/show');
        $response->assertOk()->assertJsonIsArray();
    }

    public function test_user_can_view_test()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $team=$user->teams()->first();

        $response= $this->actingAs($user)->get('/teams/'.$team->id.'/view');

        $response->assertOk();
        $this->assertSame($team->id,$response['team']['id']);

    }

    public function test_teacher_can_add_user_to_team()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $team=$user->teams()->firstOrFail();
        $anotherUser=User::factory()->create();
      
        $response= $this->actingAs($user)->post('/teams/'.$team->id.'/users/'.$anotherUser->id.'/add');
        $response->assertOk()->assertJsonPath('description','Udało się dodać użytkownika do zespołu.');
        $this->assertModelExists($anotherUser->teams()->first());
    }
    public function test_teacher_can_remove_user_from_team()
    {
        $this->seed();
        $user=User::where('email','test@example.com')->firstOrFail();
        $team=$user->teams()->firstOrFail();
        
        $anotherUser=User::factory()->create();
       
        $anotherUser->teams()->attach($team->id);
        $response= $this->actingAs($user)->delete('/teams/'.$team->id.'/users/'.$anotherUser->id.'/remove');
        $response->assertOk()->assertJsonPath('description','Udało się usunąć użytkownika z zespołu.');
        $this->assertNull($anotherUser->teams()->first());
    }
}
