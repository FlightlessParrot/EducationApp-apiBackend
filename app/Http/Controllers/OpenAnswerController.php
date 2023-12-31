<?php

namespace App\Http\Controllers;

use App\Events\NotyficationExpired;
use App\Models\Notyfication;
use App\Models\OpenAnswer;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenAnswerController extends Controller
{
    public function index(Test $test)
    {
        $this->authorize('modify',$test->team()->first());
        $openQuestions=$test->questions()->where('type','open')->get();
        
        foreach($openQuestions as $question)
        {
            $generatedQuestion=$question->generatedQuestions()->where('relevant', false)->first();
            if($generatedQuestion!=null && $generatedQuestion->openAnswer()->first()!=null)
            {
                return response(['question'=>'question','openAnswer'=>$generatedQuestion->openAnswer()->first(),'test'=>$test]);
            }

        }
        $notyfication=$test->notyfications()->where('user_id',Auth::user()->id)->first();
        NotyficationExpired::dispatch($notyfication);
        return redirect('/');
    }

    public function giveGrade(Request $request,OpenAnswer $openAnswer)
    {
        $request->validate([
            'grade'=>'required'
        ]);
        $generatedQuestion=$openAnswer->generatedQuestion()->first();
        $generatedTest=$generatedQuestion->generatedTest()->first();
        $test=$generatedTest->test()->first();
        $this->authorize('modify',$test->team()->first());
        $generatedQuestion->relevant=true;
        $generatedQuestion->answer=$request->input('grade')==='good';
        $generatedQuestion->save();

        $controller=true;
        foreach($test->generatedTests as $iteratedTest)
        {
            foreach($iteratedTest->generatedQuestions as $iteratedQuestion)
            {
                if(!$iteratedQuestion->relevant)
                {
                    $controller=false;
                }
            }
        }
        if($controller)
        {
            $notyfication=$test->notyfications()->where('type','openQuestionToCheck')->first();
           NotyficationExpired::dispatch($notyfication);
        }
        return response()->noContent();
    }
}
