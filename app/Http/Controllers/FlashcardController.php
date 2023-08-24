<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $flashcards=$user->flashcards()->get();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Flashcard $flashcard)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flashcard $flashcard)
    {
        //
    }
}
