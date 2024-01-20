<?php

namespace App\Http\Controllers;

use App\Events\EgzamStarted;
use App\Events\NotyficationExpired;
use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Team;
use App\Models\Test;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GandalfController extends Controller
{
    
    public function makeEgzam(Request $request, Team $team, GeneratedTest $generatedTest)
    {
         $this->authorize('delete',$generatedTest);
         $this->authorize('update',$team);
         $request->validate(['name'=>'required|max:250']);
        $subscription=$generatedTest->test()->first()->subscription()->first();
         $egzam=$subscription->tests()->create(['name'=>$request->input('name'),'role'=>'egzam', 'fillable'=>false,'maximum_time'=>$generatedTest->time]);
         $date=new DateTime();
         $date->modify('+3 months');
         $team->tests()->attach($egzam,['expiration_date'=>$date]);
        
        $generatedQuestions=$generatedTest->generatedQuestions()->get();
        foreach($generatedQuestions as $generatedQuestion)
        {
            $questionId=$generatedQuestion->question()->first()->id;
            $egzam->questions()->attach($questionId);
        }
        
        return response(['testId'=>$egzam->id,]);
    }

    public function generateEgzamInstanceForUser(Test $test)
    {
        $this->authorize('view',$test);
        $generatedTest=new GeneratedTest();
        $generatedTest->time=$test->time;
        $generatedTest->user_id=Auth::user()->id;
        $generatedTest->time=$test->maximum_time;
        $generatedTest->egzam=true;
        $generatedTest->gandalf=$test->gandalf;
        $generatedTest->questions_number=count($test->questions()->get());
        $test->generatedTests()->save($generatedTest);
        foreach($test->questions as $question)
        {
            $generatedQuestion=new GeneratedQuestion();
            $generatedQuestion->generated_test_id=$generatedTest->id;
            if($question->type==='open')
            {
                $generatedQuestion->relevant=false;
            }

            
            $question->generatedQuestions()->save($generatedQuestion);
        }
        $notyfication=$test->notyfications()->where('user_id',Auth::user()->id)->first();
        NotyficationExpired::dispatch($notyfication);
        
        return response(['test'=>$generatedTest->id]);
    
    }

    public function deleteEgzam(Team $team, Test $test)
    {
        
        $this->authorize('deleteEgzam', [$test, $team]);

        $test->delete();
        return response()->noContent();
    }
    //test do napisania

    public function showEgzams(Team $team)
    {
        $this->authorize('view', $team);
        $egzams=$team->tests()->where('role','egzam')->get();
       
        return response(['egzams'=>$egzams]);

    }
    public function startEgzam(Test $test)
    {
        $team=$test->team()->first();
        $this->authorize('update', $team);
        $test->fillable=true;
        $test->save();

       EgzamStarted::dispatch($test);
        return response(['testId'=>$test->id]);
    }

    
    public function findEgzam(Request $request)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests=new Collection();
            foreach(Auth::user()->teams as $team)
            {
                $teamTests=$team->tests()->where('role','egzam')->get()->filter(function(Test $test) use($request){
                    return str_contains(strtolower($test->name),strtolower($request->search));
                });
                $tests=$tests->merge($teamTests);
            }
            
        
        return response($tests);
    }
}

