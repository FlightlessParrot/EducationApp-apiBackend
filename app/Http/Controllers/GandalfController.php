<?php

namespace App\Http\Controllers;

use App\Events\EgzamStarted;
use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Team;
use App\Models\Test;
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

         $egzam=$team->tests()->create(['name'=>$request->input('name'),'role'=>'egzam', 'fillable'=>false,'maximum_time'=>$generatedTest->time]);
         error_log('Test jest wystartowany: '.$egzam->fillable);
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
    public function startEgzam(Team $team, Test $test)
    {
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
                $teamTests=$team->tests()->where('role','egzam')->where('name','like','%'.$request->search.'%')->get();
                $tests=$tests->merge($teamTests);
            }
            
        
        return response($tests);
    }
}
