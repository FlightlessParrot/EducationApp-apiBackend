<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\Test;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Test $test): bool
    {
        $bolleanValue=$user->tests()->where('id',$test->id)!=null;
            foreach($user->teams as $team)
            {
               $findTest= $team->tests()->find($test->id);
               if($findTest!=null)
               {
                $bolleanValue=true;
               }
            }
        return $bolleanValue;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Test $test): bool
    {
        
        return  $user->tests()->where('id',$test->id)!=null && $test->role==='custom';
    }
    public function updateEgzam(User $user, Test $test, Team $team): bool
    {

        $belongsToTeamRule=$team->tests()->where('id',$test->id)!=null;

        $egzamRule=$test->role==='egzam';

        $teacherRule=$team->users()->wherePivot('is_teacher', true)->where('id',$user->id)->first()!=null;
      
        return $belongsToTeamRule && $egzamRule && $teacherRule;

    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Test $test): bool
    {
        return $user->tests()->where('id',$test->id)!=null && $test->role==='custom';
    }

    public function deleteEgzam(User $user, Test $test, Team $team): bool
    {

        $belongsToTeamRule=$team->tests()->where('id',$test->id)!=null;

        $egzamRule=$test->role==='egzam';

        $teacherRule=$team->users()->wherePivot('is_teacher', true)->where('id',$user->id)->first()!=null;
      
        return $belongsToTeamRule && $egzamRule && $teacherRule;

    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Test $test): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Test $test): bool
    {
        //
    }
}
