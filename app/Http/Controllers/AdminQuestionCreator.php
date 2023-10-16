<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\Test;
use App\Models\Undercategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminQuestionCreator extends Controller
{
    public function createQuestion(Request $request, Test $test)
    {
  
        $request->validate(
            [
                'question'=>'required|max:250',
                'type'=>'required'
            ]
            );
        $all=$request->all();
        $categories=$all['categories'];
        $undercategories=$all['undercategories'];
    
        $question=$test->questions()->create([
        'question'=>$all['question'],
        'type'=>$all['type'],
        'explanation'=>$all['explanation']    
        ]);
        foreach($categories as $categoryId)
        {
            $category=Category::find($categoryId);
            $category->questions()->attach($question);
        }
        foreach($undercategories as $undercategoryId)
        {
            $undercategory=Undercategory::find($undercategoryId);
            $undercategory->questions()->attach($undercategory);
        }
        return response(['question'=>$question]);
    }

    public function addAnswers(Request $request, Question $question )
    {
       $all=$request->all();
       $answers=[];
     
       foreach($all['answers'] as $answer)
       {
       array_push($answers,($question->answers()->create([...$answer])));
       }
       return response(['answers'=>$answers]);
    }

    public function addOrder(Request $request, Question $question )
    {
        if($question->type!=='order')
        {
            return response('Bad type of question',500);
        }
        $all=$request->all();
        $squares=[];
        foreach($all['answers'] as $key=>$order)
        {
            array_push($squares, $question->squares()->create(['text'=>$order,'order'=>(int)$key])); 
        }
        return response(['squares'=>$squares]);
    }

    public function addPairs(Request $request, Question $question)
    {
        if($question->type!=='pairs')
        {
            return response('Bad type of question',500);
        }
        $all=$request->all();
        $squares=[];
        foreach($all['answers'] as $pair)
        {
           array_push($squares,[]);
           $first=$question->squares()->create(['text'=>$pair[0]]);
           $second =$question->squares()->create(['text'=>$pair[1],'brother'=>$first->id]);
           $first->brother=$second->id;
           $first->save();
            $squares[count($squares)-1][0]=$first;     
            $squares[count($squares)-1][1]=$second;
        }
        return response($squares);
    }
    
    public function addShortAnswer(Request $request, Question $question)
    {
        $request->validate([
            'answers'=>'required|max:250'
        ]);
        if($question->type!=='short-answer')
        {
            return response('Bad type of question',500);
        }
        $shortAnswer=$question->shortAnswer()->create(['answer'=>$request->input('answers')]);
        return response(['short-answer'=>$shortAnswer]);
    }
  
    public function addImageToQuestion(Request $request, Question $question )
    {
            $request->validate(['image'=>'image']);
            $path=$request->image->store('public/images/questions');
            if($question->path!==null)
            {
                Storage::delete(str_replace('/storage','public',$question->path));
            }
            $question->path=Storage::url($path);
            $question->save();
    
            return response(['image'=>$question->path]);
    }
}
