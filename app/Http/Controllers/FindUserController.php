<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FindUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $searchPhrase='%'.$request->input('search').'%';
        $users=User::where('name','like', $searchPhrase)->orWhere('name','like', $searchPhrase)->orWhere('email','like', $searchPhrase)->limit(20)->get();
        $hiddenProperties=$users->setVisible(['name','email','id']);
       
        return $hiddenProperties;
    }
}
