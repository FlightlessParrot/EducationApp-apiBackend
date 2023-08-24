<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function showCategoriesForTest(Test $test): Response
    {
       
        $questions=$test->questions()->whereNotNull('category_id')->get();
        $categories=new Collection([]);
        foreach ($questions as $question)
        {
      
            $categories->push($question->category()->first());
            $categories=$categories->unique();
        }

        return response(['categories'=>$categories]);
    }
    public function showUnderCategoriesForTest(Test $test):Response
    {

        $questions=$test->questions()->whereNotNull('undercategory_id')->get();
        $undercategories=new Collection([]);
        foreach ($questions as $question)
        {
            $undercategories->push($question->undercategory()->first());
            $undercategories=$undercategories->unique();
        }

        return response(['undercategories'=>$undercategories]);
    }

    public function showFlashCardsCategoriesAndUndercategories()
    {
        $user=Auth::user();
        $flashcards=$user->flashcards()->get();
        $categories=new Collection();
        $undercategories=new Collection();
        foreach($flashcards as $flashcard)
        {
           $categories->push($flashcard->category()->first());
            $undercategories->push($flashcard->undercategory()->first());
        }
        $categories=$categories->unique();
        $undercategories=$undercategories->unique();

        return response(['categories'=>$categories,'undercategories'=>$undercategories]);
    }
}
