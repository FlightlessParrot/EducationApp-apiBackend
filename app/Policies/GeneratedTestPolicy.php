<?php

namespace App\Policies;

use App\Models\GeneratedTest;
use App\Models\User;
use Error;
use Illuminate\Auth\Access\Response;

class GeneratedTestPolicy
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
    public function view(User $user, GeneratedTest $generatedTest): bool
    {
       return $user->generatedTests()->find($generatedTest->id)!=null;
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
    public function update(User $user, GeneratedTest $generatedTest): bool
    {
        return $user->generatedTests()->find($generatedTest->id)!=null;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeneratedTest $generatedTest): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeneratedTest $generatedTest): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeneratedTest $generatedTest): bool
    {
        //
    }
}
