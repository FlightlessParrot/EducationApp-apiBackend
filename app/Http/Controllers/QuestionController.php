<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    public function find(Request $request)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests=Auth::user()->tests()->get();
        $questions=collect([]);
        foreach($tests as $test )
        {
            $newQuestions=$test->questions()->where('question','like','%'.$request->search.'%')->get();
            $questions=$questions->concat($newQuestions);
        }
        return $questions->chunk(12)[0];
   
    }
    public function findUnowned(Request $request, Test $test)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests=Auth::user()->tests()->get();
        $questions=collect([]);
        foreach($tests as $ownTest )
        {
            if($test->id!==$ownTest->id)
            {
            $newQuestions=$ownTest->questions()->where('question','like','%'.$request->search.'%')->get();
            
            foreach($newQuestions as $newQuestion)
            {
                
                if($test->questions()->find($newQuestion->id) !=null)
                {
                    $newQuestions=$newQuestions->except($newQuestion->id);
                }        
                
            } 
            
            $questions=$questions->concat($newQuestions);
            $questions=$questions->unique('id');
            }
        }
       
        return Response( count($questions) ? $questions->chunk(12)[0] : $questions);
       
    }
    public function findOwned(Request $request, Test $test):Response
    {
        $request->validate(['search'=>'nullable|max:250']);
        $this->authorize('view', $test);

        return Response($test->questions()->where('question','like','%'.$request->search.'%')->limit(12)->get());
       
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test, Question $question)
    {
       
       $this->authorize('delete',$test);
        $this->authorize('detach', $question);
        if($question->custom) 
        {
            $question->answers()->delete(); 
            $question->delete();
        }
        else{
            $question->tests()->detach($test->id);} 
        return response()->noContent();
    }

    public function attach(Test $test, Question $question)
    {
        $this->authorize('update',$test);
        $this->authorize('attach', $question);
        $test->questions()->attach($question->id);
        return response()->noContent();
    }
}
