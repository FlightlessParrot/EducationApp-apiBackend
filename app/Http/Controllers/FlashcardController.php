<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Flashcard;
use App\Models\Subscription;
use App\Models\Undercategory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user=Auth::user();
        $categories=$request->input('categories');
        $undercategories=$request->input('undercategories');
        $limit=(int)$request->input('limit');
        $limitNumber=$limit ? $limit : 10;
        $flashcards=new Collection();
        foreach($user->subscriptions as $subscription)
        {
            $flashcards=$flashcards->merge($subscription->flashcards()->get());
        }
     
        $chosenFlashcards=new Collection();
       
        
            if($categories && !$undercategories)
            {
        foreach($categories as $category)
        {
            $chosenFlashcards=$chosenFlashcards->merge($flashcards->whereRelation('categories', 'id',$category));
        }}
        if($undercategories)
        {
        foreach($undercategories as $undercategory)
        {
            $chosenFlashcards=$chosenFlashcards->merge($flashcards->whereRelation('undercategories', 'id', $undercategory));
        }}
        
        if(!$undercategories && !$categories)
        {
            $chosenFlashcards=$flashcards;
        }
        if(count($chosenFlashcards) >$limitNumber)
        {
        $chosenFlashcards=$chosenFlashcards->unique()->random($limitNumber);
        }
        $flashcardsWithCategories=new Collection();
        foreach($chosenFlashcards as $flashcard)
        {
            $categories=$flashcard->categories()->get();
            $undercategories=$flashcard->undercategories()->get();
            $flashcard['undercategories']=$undercategories;
            $flashcard['categories']=$categories;
            $flashcardsWithCategories->push($flashcard);
        }
        return response(['flashcards'=>$flashcardsWithCategories]);

    }

    public function find(Request $request, Subscription $subscription)
    {
        $search=$request->input('search');
        $flashcards=$subscription->flashcards()->where(function(Builder $querry) use ($search) {
            return $querry->where('question','like','%'.$search.'%')->orWhere('answer','like','%'.$search.'%');
        })->get();
        $flashcardsWithCategories=new Collection();
        foreach($flashcards as $flashcard)
        {
            $categories=$flashcard->categories()->get();
            $undercategories=$flashcard->undercategories()->get();
            $flashcard['undercategories']=$undercategories;
            $flashcard['categories']=$categories;
            $flashcardsWithCategories->push($flashcard);

        }

        return response(['flashcards'=>$flashcardsWithCategories]);   
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question'=>'required|max:250',
            'answer'=>'required|max:10000',
            'categories'=>'nullable',
            'undercategories'=>'nullable',
            
        ]);
        $flashcard=Flashcard::make();
        $flashcard->question=$request->question;
        $flashcard->answer=$request->answer;
        $flashcard->save();
      
        foreach($request->categories as $categoryId)
        {
            $category=Category::findOrFail($categoryId);
            $flashcard->categories()->attach($category);
        }
        foreach($request->undercategories as $undercategoryId)
        {
            
    
            $undercategory=Undercategory::find($undercategoryId);
            $flashcard->undercategories()->attach($undercategory);
            
        }
        foreach($request->subscriptions as $subscription)
        {
            $flashcard->subscriptions()->attach($subscription);
        }
        
        return response(['flashcard'=>$flashcard, ]);
    }


    public function addImage(Request $request,Flashcard $flashcard)
    {
        $request->validate([
            'image'=>'image',
           
        ]);
        if($flashcard->path!==null)
        {
         
          Storage::delete(str_replace('/storage','public',$flashcard->path));
        }
        $path=$request->image->store('public/images/flashcards');
        $flashcard->path=Storage::url($path);
        $flashcard->save();

        return response(['image'=>$flashcard->path]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Flashcard $flashcard)
    {
        $flashcard['subscriptions']=$flashcard->subscriptions()->get();
        $categories=$flashcard->categories()->get();
        $undercategories=$flashcard->undercategories()->get();
        $flashcard['undercategories']=$undercategories;
        $flashcard['categories']=$categories;
     
        return response(['flashcard'=>$flashcard]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flashcard $flashcard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flashcard $flashcard)
    {
        $request->validate([
            'question'=>'required|max:250',
            'answer'=>'required|max:10000',
            'categories'=>'nullable',
            'undercategories'=>'nullable',
        ]);

  
        $flashcard->question=$request->question;
        $flashcard->answer=$request->answer;
        


        foreach($flashcard->categories as $categoryId)
        {
            $category=Category::find($categoryId);
            $flashcard->categories()->detach($category);
        }
        foreach($flashcard->undercategories as $undercategoryId)
        {
            $undercategory=Category::find($undercategoryId);
            $flashcard->undercategories()->detach($undercategory);
        }
        foreach($flashcard->subscriptions as $subscription)
        {
            $flashcard->subscriptions()->detach($subscription);
        }


        foreach($request->categories as $categoryId)
        {
            $category=Category::find($categoryId);
            $flashcard->categories()->attach($category);
        }
        foreach($request->undercategories as $undercategoryId)
        {
            $undercategory=Category::find($undercategoryId);
            $flashcard->undercategories()->attach($undercategory);
        }
        foreach($request->subscriptions as $subscription)
        {
            $flashcard->subscriptions()->attach($subscription);
        }
        $flashcard->save();

        return response(['flashcard'=>$flashcard]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flashcard $flashcard)
    {
        if($flashcard->path!==null)
        {
            Storage::delete(str_replace('/storage','public',$flashcard->path));
        } 
        $bool=$flashcard->delete();
        if(!$bool)
        {
            return response('Nie udało się usunąć elementu',500);
        }
        return response(['flashcard'=>$flashcard]);
    }
}
