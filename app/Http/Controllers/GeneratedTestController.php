<?php

namespace App\Http\Controllers;

use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Type\ObjectType;
use SebastianBergmann\Type\VoidType;

class GeneratedTestController extends Controller
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
        
        $user=Auth::user();
         $test=Test::find($request->test_id);
         $questionsNumber=$request->questionsNumber ?: 10;
         $questions = $test->questions()->inRandomOrder()->limit($questionsNumber)->get();
        $myTest=$user->generatedTests()->create([
            'egzam'=>$request->egzam,
            'time'=>strtotime($request->time),
            'test_id'=>$request->test_id,
            'questions_number'=>$questionsNumber
        ]);
      
           
      
        

        
      
        foreach($questions as $question)
        {
            $generatedQuestion= new GeneratedQuestion(['question_id'=>$question->id]);
            $myTest->generatedQuestions->save($generatedQuestion);

        }
        return ['test'=>$myTest, 'questions'=>$questions];
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneratedTest $generatedTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneratedTest $generatedTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneratedTest $generatedTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneratedTest $generatedTest)
    {
        //
    }
}
