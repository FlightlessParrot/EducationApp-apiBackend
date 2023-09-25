<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
   public function upgrade(User $user)
   {
    If($user->role==='student')
    {
        $user->role='premium';
        $user->save();
        return response(['user'=>$user]);
    }else{
        return response('It is not a student',500);
    }
    
   }
   public function downgrade(User $user)
   {
    If($user->role==='premium')
    {
        $user->role='student';
        $user->save();
        return response(['user'=>$user]);
    }else{
        return response('It is not a premium user',500);
    }
    
   }
}
