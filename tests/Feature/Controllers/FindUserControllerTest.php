<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindUserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_teacher_can_find_students(): void
    {
      $this->seed();
      $user=User::where('email','test@example.com')->first();
      $lostUser=User::whereNot('email','test@example.com')->first();
      $response=$this->actingAs($user)->get('users/find?search='.$lostUser->name);

      $response->assertOk()->assertJsonIsArray();
    }
    public function test_student_cannot_find_anothers(): void
    {
      $this->seed();
      $user=User::where('role','student')->first();
      $lostUser=User::whereNot('id',$user->id)->first();
      $response=$this->actingAs($user)->get('users/find?search='.$lostUser->name);
      
      $response->assertUnauthorized();
    }
}
