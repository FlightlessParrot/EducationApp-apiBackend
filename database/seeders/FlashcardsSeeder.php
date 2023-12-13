<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Flashcard;
use App\Models\Undercategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashcardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::where('email', 'test@example.com')->first();
        $subscription=$user->subscriptions()->first();
        for($i=0; $i<2; $i++)
        {
        $flashcards=Flashcard::factory()->count(10)->make();
        $category=Category::factory()->create();
        $undercategory=Undercategory::factory()->create();
        $undercategory->category()->associate($category);
        $undercategory->save();
        foreach($flashcards as $flashcard)
        {
        $subscription->flashcards()->save($flashcard);
        $flashcard->categories()->attach($category);
        $flashcard->undercategories()->attach($undercategory);
     
        }
    }
    }
}
