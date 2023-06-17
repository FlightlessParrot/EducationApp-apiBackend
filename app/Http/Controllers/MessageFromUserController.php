<?php

namespace App\Http\Controllers;

use App\Mail\UserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessageFromUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $type)
    {
        $request->validate([
            'title'=> ['required'],
            'message'=> ['required'],
            'mail'=>['nullable','email']
        ]);
        $array=[
            'title'=>$request->input('title'),
            'message'=>$request->input('message'),
            'sirOrLady'=>$request->input('sirOrLady'),
            'mail'=>$request->input('mail'),
        ];

        $user=Auth::user();
        Mail::send(new UserMail($array, $user));


        return response()->noContent(202);
    }
}
