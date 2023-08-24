<?php

namespace Database\Seeders;

use App\Models\Flashcard;
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

        
        $flashcards=Flashcard::factory()->count(10)->make();
        foreach($flashcards as $flashcard)
        {
        $user->flashcards()->save($flashcard);
        }
    }
}
