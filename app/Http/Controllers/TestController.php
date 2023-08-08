<?php

namespace App\Http\Controllers;

use App\Models\Question;
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
       
        $test=$user->tests()->create(['name'=>$request->name, 'custom'=>true]);
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
        $tests=Auth::user()->tests()->where('name','like','%'.$request->search.'%')->where('custom', $request->custom==='true' )->get();
        return $tests;
    }
   
}
