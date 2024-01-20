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
        $undercategory=Undercategory::factory()->create();
        $undercategory->category()->associate($category);
        $undercategory->save();
        $questions=Question::inRandomOrder()->limit(20)->get();
        
        foreach ($questions as $question)
        {
           
            $question->categories()->attach($category);
            $question->undercategories()->attach($undercategory);
           
        }
        }
        }
}