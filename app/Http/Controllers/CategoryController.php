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
       
        $questions=$test->questions()->get();
        $categories=new Collection([]);
        foreach ($questions as $question)
        {
            foreach ($question->categories as $category)
            {
            $categories->push($category);
            }
            $categories=$categories->unique();
        }

        return response(['categories'=>$categories]);
    }
    public function showUnderCategoriesForTest(Test $test):Response
    {

        $questions=$test->questions()->get();
        $undercategories=new Collection([]);
        foreach ($questions as $question)
        {
            foreach($question->undercategories as $undercategory)
            {
                $undercategories->push($undercategory);
            }
            $undercategories=$undercategories->unique();
        }

        return response(['undercategories'=>$undercategories]);
    }

    public function showFlashCardsCategoriesAndUndercategories()
    {
        $user=Auth::user();
        $subscriptions=$user->subscriptions()->get();
        $flashcards=new Collection();
        foreach($subscriptions as $subscription)
        {
            $flashcards=$flashcards->merge($subscription->flashcards()->get());
        }
        $flashcards=$flashcards->unique();
        $categories=new Collection();
        $undercategories=new Collection();
        foreach($flashcards as $flashcard)
        {
            $categories=$flashcard->categories()->get();
            $categories->push(...$categories);
            
            $undercategories=$flashcard->undercategories()->get();
            $undercategories->push(...$undercategories);
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
        $request->validate(['name'=>'required|max:250|unique:categories','category'=>'required|Numeric']);

        $category=Category::findOrFail($request->category);
        $undercategory=Undercategory::create(['name'=>$request->name]);
        $undercategory->category()->associate($category);
        $undercategory->save();
        return response(['undercategory'=>$undercategory]);
    }

    public function attachUndercategory(Category $category, Undercategory $undercategory)
    {
        $undercategory=$undercategory->category()->associate($category);
        
        return response(['undercategory'=>$undercategory]);
    }

    public function deleteCategory(Category $category)
    {
        foreach($category->undercategories as $undercategory){
            $undercategory->delete();
        }
        $category->delete();
        return response(['category'=>$category]);
    }

    public function deleteUndercategory(Undercategory $undercategory)
    {
        $undercategory->delete();
        return response(['undercategory'=>$undercategory]);
    }
}
