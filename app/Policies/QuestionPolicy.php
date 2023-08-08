<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
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
    public function view(User $user, Question $question): bool
    {
        $test= $question->tests()->whereRelation('users', 'id', $user->id)->first();
        return $test!=null;
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
    public function update(User $user, Question $question): bool
    {
      
    }
    public function attach(User $user, Question $question):bool
    {
        $test= $question->tests()->whereRelation('users', 'id', $user->id)->first();
        return $test!=null;
    }
    public function detach(User $user, Question $question):bool
    {
        $test= $question->tests()->where('custom',true)->whereRelation('users', 'id', $user->id)->first();
        return $test!=null;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Question $question): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        //
    }
}
