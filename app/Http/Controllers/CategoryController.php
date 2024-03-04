<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUndercategoryRequest;
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
                $undercategory->categories=$undercategory->categories()->get();
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
            $flashcardCategories=$flashcard->categories()->get();
            $categories->push(...$flashcardCategories);
            
            $flashcardUndercategories=$flashcard->undercategories()->get();
            
            foreach($flashcardUndercategories as $undercategory)
            {
                
                $undercategory->categories=$undercategory->categories()->get();
                $undercategories->push($undercategory);
            }
            
            
        }
        $categories=$categories->unique();
        $undercategories=$undercategories->unique();

        return response(['categories'=>$categories,'undercategories'=>$undercategories]);
    }

    public function showAllCategoriesAndUndercategories()
    {
        $categories=Category::all();
        $undercategories=Undercategory::all();
        $undercategoriesWithCategories=new Collection();
        foreach($undercategories as $undercategory)
        {
            $undercategory->categories=$undercategory->categories()->get();
            $undercategoriesWithCategories->push($undercategory);
        }
        return response(['categories'=>$categories,'undercategories'=>$undercategoriesWithCategories]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name'=>'required|max:250|unique:undercategories']);
        $category=Category::create(['name'=>$request->name]);
        return response(['category'=>$category]);
    }

    public function storeUndercategory(Request $request)
    {
        $request->validate(['name'=>'required|max:250|unique:categories','categories'=>'required']);
        $undercategory=Undercategory::create(['name'=>$request->name]);
        foreach($request->categories as $category)
        {
        $category=Category::findOrFail($category);
        
        $undercategory->categories()->attach($category);
        }
        
        return response(['undercategory'=>$undercategory]);
    }

    public function attachUndercategory(Category $category, Undercategory $undercategory)
    {
        $undercategory=$undercategory->categories()->attach($category);
        
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

    /**
     * Get the category model and an array of all categories.
     * 
     * @param Category $undercategory Required category model.
     * 
     * @return Response Return category and all categories models.
     * 
     */
    public function showCategory(Category $category)
    {

        return response(['category'=>$category, 'categories'=>Category::all()]);
    }
    public function editCategory(Request $request, Category $category) : Response
    {
        $request->validate(['name'=>'required|string']);
        $category->name=$request->name;
        $category->save();
        return response(['isSuccessful'=>true, 'category'=>$category]);
    }

    /**
     * Get the undercategory model and an array of all undercategories models.
     * 
     * @param Undercategory $undercategory Required undercategory model
     * 
     * @return Response Return the undercategory and all undercategories models.
     * 
     */
    public function showUndercategory(Undercategory $undercategory) : Response
    {
        $masterCategories=$undercategory->categories()->get();
        $undercategory->categories=$masterCategories;
        return response(['undercategory'=>$undercategory, 'undercategories'=>Category::all()]);
    }

    /**
     * Update Undercategory.
     * 
     * @param  EditUndercategoryRequest request - Incomming request.
     * @param Undercategory $undercategory - Updated Undercategory.
     * 
     * @return Response
     * 
     */
    public function editUndercategory(EditUndercategoryRequest $request, Undercategory $undercategory) : Response
    {
        

        $undercategory->name=$request->name;
        $undercategory->categories()->detach();
        foreach($request->categories as $categoryId)
        {
            $undercategory->categories()->attach($categoryId);
        }
        $undercategory->save();
        return response(['isSuccessful'=>true,'undercategory'=>$undercategory]);

    }
}
