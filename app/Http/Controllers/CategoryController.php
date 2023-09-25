<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Test;
use App\Models\Undercategory;
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

    public function showAllCategoriesAndUndercategories()
    {
        $categories=Category::all();
        $undercategories=Undercategory::all();
        return response(['categories'=>$categories,'undercategories'=>$undercategories]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name'=>'required|max:250|unique:undercategories']);
        $category=Category::create(['name'=>$request->name]);
        return response(['category'=>$category]);
    }

    public function storeUndercategory(Request $request)
    {
        $request->validate(['name'=>'required|max:250|unique:categories']);
        $undercategory=Undercategory::create(['name'=>$request->name]);
        return response(['undercategory'=>$undercategory]);
    }

    public function deleteCategory(Category $category)
    {

        $category->delete();
        return response(['category'=>$category]);
    }

    public function deleteUndercategory(Undercategory $undercategory)
    {
        $undercategory->delete();
        return response(['undercategory'=>$undercategory]);
    }
}
