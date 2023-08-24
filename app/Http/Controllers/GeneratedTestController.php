<?php

namespace App\Http\Controllers;

use App\Events\OpenAnswersWritten;
use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Square;
use App\Models\Undercategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class GeneratedTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function showStatistics(GeneratedTest $generatedTest) 
    {
        $this->authorize('view', $generatedTest);
        $allAnswers= $generatedTest->generatedQuestions()->get();
        $correctAnswers=$generatedTest->generatedQuestions()->where('answer', true)->get();
        $wrongAnswers=$generatedTest->generatedQuestions()->where('answer',false)->get();
        $nonAnswers=$generatedTest->generatedQuestions()->whereNull('answer');
        $ojojAnswer=$wrongAnswers->merge($nonAnswers)->unique();
        $pass=(count($correctAnswers)/count($allAnswers))*100>$generatedTest->gandalf;
        $responseData=[
            'generatedTest'=>$generatedTest,
            'correctAnswers'=>$correctAnswers,
            'wrongAnswers'=>$ojojAnswer,
            'allAnswers'=>$allAnswers,
            'pass'=>$pass
        ];
        return $responseData;

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) :Response
    {
       
        $user = Auth::user();
        $questionsNumber = $request->input('questionsNumber') ?: 10;
        $testId = $request->input('test_id') ? $request->input('test_id') : null;
        $categories=$request->input('categories');
        $undercategories=$request->input('undercategories');
        $gandalf=$request->input('gandalf');
        $test = $user->tests()->findOrFail($testId);
        $input=$request->all();
        $questions= new Collection();
        $questionsByTypes=new Collection();
        foreach($input as $key => $value)
        {
            if($key!=='categories' && $key!=='undercategories')
            {
            $chosenQuestions=$test->questions()->where('type', $value)->get();
            $questionsByTypes=$questionsByTypes->merge($chosenQuestions);
            }
        }

        if($categories || $undercategories )
        {  
            foreach($categories as $category)
            $questions=$questions->merge($questionsByTypes->where('category_id',$category));
            

            foreach($undercategories as $undercategory)
            {
            $questions=$questions->merge($questionsByTypes->where('undercategory_id',$undercategory));
            }
            $questions=$questions->unique();
        }else{
            $questions=$questionsByTypes;
        }
        
        if(count($questions)>$questionsNumber)
        {
        $questions=$questions->random($questionsNumber);
        }
        $myTest = $user->generatedTests()->create([
            'egzam' => (bool)$request->input('egzam'),
            'time' => $request->time,
            'test_id' => $testId,
            'questions_number' => $questionsNumber,
            'gandalf'=>$gandalf
        ]);
        
        foreach ($questions as $question) {
            $generatedQuestion = new GeneratedQuestion(['question_id' => $question->id]);
            $myTest->generatedQuestions()->save($generatedQuestion);
        }
        return response(['test' => $myTest->id]);
    }


    /**
     * Display the specified resource.
     */
    public function show(GeneratedTest $generatedTest)
    {
       $this->authorize('view', $generatedTest);
         $test= Array();
        
        foreach ($generatedTest->generatedQuestions as $generatedQuestion)
        {
            $question=$generatedQuestion->question()->first();
            $position=[
                
                'question'=>$question,
                'answers'=>$question->answers()->inRandomOrder()->get(),
                'squares'=>$question->squares()->inRandomOrder()->get()  
            ];
            array_push($test, $position);
        }
        shuffle($test);
        return ['generatedTest'=>$generatedTest, 'test'=>$generatedTest->test()->first(),'qas'=>$test];
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
        //Pokrycie testem ekonomicznie niezasadne
        
        $this->authorize('update', $generatedTest);
        if($generatedTest->solved)
        {
           return response('Generated test has been solved already.',403);
        }
        $generatedTest->duration = $request->input('additionalData.time');
        $generatedTest->save();
        $generatedQuestions = $generatedTest->generatedQuestions()->get();
        foreach ($generatedQuestions as $generatedQuestion) {
            $question=$generatedQuestion->question()->firstOrfail();
            $questionId = $question->id;
            $questionWithAnswer=$request->input('answers.' . (string)$questionId);
            $correct = true;
           if($questionWithAnswer==null)
           {continue;}
            switch ($question->type) {
                case 'one-answer':
                case 'many-answers':
                   if(count($questionWithAnswer)!=count($question->answers()->get()))
                   {
                    $correct = false;break;
                    
                   }
                   
                    foreach ($questionWithAnswer as $key => $givenAnswer) {
                        
                        $answer = $question->answers()->find($key);
                   
                        if (filter_var($givenAnswer, FILTER_VALIDATE_BOOLEAN) != $answer->correct) {
                            $correct = false;
                        }
                    }
                    break;

                case 'pairs':
                    if(count($questionWithAnswer)!==count($question->squares()->get())/2)
                   {
                    $correct = false;break;
                   }
                    foreach ($questionWithAnswer as $answer) {
                        $square = Square::find($answer[0]);
                        if ($square->brother !==(int) $answer[1]) {
                            $correct = false;
                        }
                    }
                    break;
                case 'order':
                    if(count($questionWithAnswer)!=count($question->squares()->get()))
                    {
                        $correct = false;break;
                       }
                    foreach ($questionWithAnswer as $key => $order) {
                        $square = Square::find($key);
                        if ($square->order !== $order) {
                            $correct = false;
                        }
                    }
                    break;
                case 'open':
                    $correct=false;
                    $generatedQuestion->openAnswer()->create(['answer'=>$questionWithAnswer]);
                    $test=$generatedTest->test()->first();
                    OpenAnswersWritten::dispatch($test);
                    break;

            } 
           $generatedQuestion->answer=$correct;
           $generatedQuestion->save();
           
        }
        $generatedTest->solved=true;
           $generatedTest->save();
       return response()->noContent();
    }

    public function getCorrectAnswerData(GeneratedTest $generatedTest)
    {
        $generatedQuestions=$generatedTest->generatedQuestions()->get();
        $correct=[];
        foreach($generatedQuestions as $generatedQuestion)
        {
            if(!$generatedQuestion->openAnswer()->first())
            {
            $question=$generatedQuestion->question()->first();
            $correct[$question->id]=$generatedQuestion->answer;
            }
        }
        return response(['correct'=>$correct],200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneratedTest $generatedTest)
    {
        $this->authorize('delete', $generatedTest);
        foreach($generatedTest->generatedQuestions as $generatedQuestion)
        {
            $generatedQuestion->delete();
        }
        $generatedTest->delete();

        return response()->noContent();
    }
}
