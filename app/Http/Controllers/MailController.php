<?php

namespace App\Http\Controllers;

use App\Mail\Newsletter as MailNewsletter;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sentMailToGroup()
    {

    }

    public function sentMailToAll(Request $request)
    {
        $request->validate([
            'subject'=>'required|max:250',
            'text'=>'required'
        ]);
        $news=Newsletter::create(['text'=>$request->text,
        'subject'=>$request->subject]);

        foreach(User::all() as $user)
        {
            Mail::to($user)->send(new MailNewsletter($news));
        }

    
        return response(['newsletter'=>$news]);
    }
}
