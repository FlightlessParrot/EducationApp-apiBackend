<?php

namespace App\Http\Controllers;

use App\Models\GeneratedTest;
use App\Models\Question;
use App\Models\Team;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request):Response
    {
        $request->validate([
            'name'=>['required','max:250'],
            'test_id'=>['nullable','integer']
        ]); 
        $user=Auth::user();
       
        $test=$user->tests()->create(['name'=>$request->name, 'role'=>'custom']);
        if($request->test_id)
        {
          foreach($user->tests()->find($request->test_id)->questions as $question)
          {
            $test->questions()->attach($question->id);
          } 
        }
        return response()->noContent();
    }

    
    public function removeAllQuestions(Test $test)
    {
       
        $this->authorize('delete', $test);
       
       $test->questions()->where('custom', true)->delete();
       $test->questions()->where('custom', false)->detach();
     
        return response()->noContent();

    }

    
    
    public function destroy(Test $test)
    {
   
        $this->authorize('delete', $test);
        $test->delete();
        return response()->noContent();
        
    }

    public function find(Request $request)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests=Auth::user()->tests()->where('name','like','%'.$request->search.'%')->where('role', $request->input('custom')==='true' ? 'custom': 'general' )->get();
        if($request->input('custom')==='false')
        {
            foreach(Auth::user()->teams as $team)
            {
                $teamTests=$team->tests()->where('role','!=','egzam')->where('name','like','%'.$request->search.'%')->get();
                $tests=$tests->merge($teamTests);
            }
            
        }
        return response($tests);
    }
   
}
