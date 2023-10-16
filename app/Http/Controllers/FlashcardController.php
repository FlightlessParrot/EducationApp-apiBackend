<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Flashcard;
use App\Models\Subscription;
use Hamcrest\Type\IsNumeric;
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
       
        
            if($categories)
            {
        foreach($categories as $category)
        {
            $chosenFlashcards=$chosenFlashcards->merge($flashcards->where('category_id', $category));
        }}
        if($undercategories)
        {
        foreach($undercategories as $undercategory)
        {
            $chosenFlashcards=$chosenFlashcards->merge($flashcards->where('undercategory_id', $undercategory));
        }}
        
        if(!$undercategories && !$categories)
        {
            $chosenFlashcards=$flashcards;
        }
        if(count($chosenFlashcards) >$limitNumber)
        {
        $chosenFlashcards=$chosenFlashcards->unique()->random($limitNumber);
        }

        return response(['flashcards'=>$chosenFlashcards]);

    }

    public function find(Request $request, Subscription $subscription)
    {
        $search=$request->input('search');
        $flashcards=$subscription->flashcards()->where(function(Builder $querry) use ($search) {
            return $querry->where('question','like','%'.$search.'%')->orWhere('answer','like','%'.$search.'%');
        })->get();
        return response(['flashcards'=>$flashcards]);   
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
            'category_id'=>'integer|nullable',
            'undercategory_id'=>'integer|nullable',
            
        ]);
        $flashcard=Flashcard::make();
        $flashcard->question=$request->question;
        $flashcard->answer=$request->answer;
        $flashcard->category_id= $request->category_id ;
        $flashcard->undercategory_id= $request->undercategory_id ;
        $flashcard->save();
        foreach($request->subscriptions as $subscription)
        {
            $flashcard->subscriptions()->attach($subscription);
        }
        
        return response(['flashcard'=>$flashcard]);
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
            'category_id'=>'integer|nullable',
            'undercategory_id'=>'integer|nullable'
        ]);

  
        $flashcard->question=$request->question;
        $flashcard->answer=$request->answer;
        $flashcard->category_id= $request->category_id ;
        $flashcard->undercategory_id= $request->undercategory_id ;
        foreach($flashcard->subscriptions as $subscription)
        {
            $flashcard->subscriptions()->detach($subscription);
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
