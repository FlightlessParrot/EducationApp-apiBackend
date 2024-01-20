<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\Team;
use App\Models\Test;
use App\Models\Undercategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function createEgzamQuestion(Request $request,Team $team, Test $test)
    {
        $this->authorize('updateEgzam',[$test, $team]);
       $request->validate([
        'question'=>'required|max:250',
        'type'=>'required',
        'photo'=>'nullable|mimes:jpg,webp,png'
       ]);
       $data=$request->all();
       $data['custom']=true;
       $question=$test->questions()->create($data);
       if ($request->hasFile('image')) {
        $path=$request->image->store('public/images/questions');
        $question->path=Storage::url($path);
        $question->save();

        }
        
       return response(['questionId'=>$question->id,'question'=>$question]);
       
    }
    public function addPathtoEgzamQuestion(Request $request, Team $team, Test $test, Question $question)
    {
        $this->authorize('updateEgzam',[$test, $team]);
        
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
    public function show(Question $question): Response
    {
        $data=$question->toArray();
        $data['categories']=$question->categories()->get();
        $data['undercategories']=$question->undercategories()->get();
        $data['answers']=$question->answers()->get();
        $data['squares']=$question->squares()->get();
        return Response(['question'=>$data]);
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
        $tests=Auth::user()->tests();
        $questions=collect([]);
        foreach($tests as $test )
        {
            $newQuestions=$test->questions()->where('question','like','%'.$request->search.'%')->get();
            $questions=$questions->concat($newQuestions);
        }
        return $questions;
   
    }
    public function findUnowned(Request $request, Test $test)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests=Auth::user()->tests();
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

        $categories=$request->input('categories');
        $undercategories=$request->input('undercategories');
        $questionsByCategories=new Collection();
        $questionsByUndercategories=new Collection();
        if($categories && !$undercategories){
            foreach($categories as $categoryId)
            {
                $category=Category::find($categoryId);
                $questionsOfCategory=$category->questions()->get();
                $searchedQuestionFilteredByCategory=$questions->intersect($questionsOfCategory); 
                $questionsByCategories=$questionsByCategories->merge($searchedQuestionFilteredByCategory);
            }
        }
        if($undercategories)
        {
            foreach($undercategories as $undercategoryId)
            {
                $undercategory=Undercategory::find($undercategoryId);
                $questionsOfUndercategory=$undercategory->questions()->get();
                $searchedQuestionFilteredByUndercategory=$questions->intersect($questionsOfUndercategory);
                $questionsByUndercategories=$questionsByUndercategories->merge($searchedQuestionFilteredByUndercategory);
            }
        }
        if($questionsByCategories->isNotEmpty() || $questionsByUndercategories->isNotEmpty())
        {
            $questions=$questionsByCategories->merge($questionsByUndercategories);
        }

       
        return Response( $questions);
       
    }
    public function findOwned(Request $request, Test $test):Response
    {
        $request->validate(['search'=>'nullable|max:250']);
        $this->authorize('view', $test);
        $questions=$test->questions()->where('question','like','%'.$request->search.'%')->get();
        $categories=$request->input('categories');
        $undercategories=$request->input('undercategories');
        $questionsByCategories=new Collection();
        $questionsByUndercategories=new Collection();
        if($categories)
        {
            foreach($categories as $categoryId)
            {
                $category=Category::find($categoryId);
                $questionsOfCategory=$category->questions()->get();
                $searchedQuestionFilteredByCategory=$questions->intersect($questionsOfCategory);
                $questionsByCategories=$questionsByCategories->merge($searchedQuestionFilteredByCategory);
                $questionsByCategories->unique()->values();
            }
        }
        if($undercategories)
        {
            foreach($undercategories as $undercategoryId)
            {
                $undercategory=Undercategory::find($undercategoryId);
                $questionsOfUndercategory=$undercategory->questions()->get();
                $searchedQuestionFilteredByUndercategory=$questions->intersect($questionsOfUndercategory);
                $questionsByUndercategories=$questionsByUndercategories->merge($searchedQuestionFilteredByUndercategory);
                $questionsByUndercategories->unique()->values();
            }
        }
        if($questionsByCategories->isNotEmpty() || $questionsByUndercategories->isNotEmpty())
        {
            $questions=$questionsByCategories->merge($questionsByUndercategories);
        }
        return Response( $questions );
       
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

     public function remove(Question $question)
     {
        $question->delete();

        return response()->noContent();
     }
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
        $test->questions()->attach($question->id);
        return response()->noContent();
    }
}
