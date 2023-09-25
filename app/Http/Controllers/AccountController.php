<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAdress;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    
    public function show()
    {
        $adress=Auth::user()->userAdress()->first();

        return Response(['adress'=>$adress,'user'=>Auth::user() ]);
    }

    public function update(Request $request)
    {
        $user=Auth::user();
         $userAdress=$user->userAdress()->first();
         if($userAdress==null)
         {
            $userAdress=new UserAdress(['user_id'=>$user->id]);
         }
        $input=$request->all(); 
        $userAdress['adress']=$input['adress'];
        $userAdress['nip']=$input['nip'];
        $userAdress['city']=$input['city'];
        $userAdress['postal_code']=$input['postal_code'];
        $user['name']=$input['name'];
        $user->save();
        $userAdress->save();
        return response()->noContent();
        
    }

    public function toogleNewsletter()
    {
        $userAdress=Auth::user()->userAdress()->first();
        $userAdress->newsletter=!(bool)$userAdress->newsletter;
        $userAdress->save();
        return response()->noContent();
    }
}
