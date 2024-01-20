<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FindTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search='%'.$request->search.'%';
        $users=User::where('role','premium')->where(function (Builder $query) use ($search){
            return $query->where('name','like', $search)->orWhere('email','like',$search);
        })->get();

        return response(['users'=>$users]);
    }
}
