<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use App\Models\Undercategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0;$i<=2;$i++)
        {
        $category=Category::factory()->create();
        $questions=Question::inRandomOrder()->limit(20)->get();
        
        foreach ($questions as $question)
        {
           
            $question->category()->associate($category);
            $question->save();
        }
        }
    
    for($i=0;$i<=2;$i++)
        {
       $undercategory=Undercategory::factory()->create();
        
        $questions=Question::whereNotNull('category_id')->inRandomOrder()->limit(20)->get();
        foreach ($questions as $question)
        {
        $question->undercategory()->associate($undercategory);
        $question->save();
        }
        }
        }
}