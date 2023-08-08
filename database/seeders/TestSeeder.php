<?php

namespace Database\Seeders;

use App\Models\GeneratedTest;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        // $user=User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password'=>Hash::make('TestPassword')
        // ]);

        $user=User::where('email', 'test@example.com')->first();
        //first test
        $test=Test::factory()->create(['name' =>'Test Test']);
       for($i=0;$i<50;$i++)
       {
        $question=\App\Models\Question::factory()->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        for($i=0;$i<50;$i++)
       {
        $question=\App\Models\Question::factory()->create(['type'=>'many-answers']);
        \App\Models\Answer::factory()->for($question)->count(2)->create();
        \App\Models\Answer::factory()->for($question)->count(2)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        for($i=0;$i<50;$i++)
       {
        $question=\App\Models\Question::factory()->create(['type'=>'pairs']);
        for($a=0;$a<4; $a++)
        {
        $firstSquare=\App\Models\Square::factory()->for($question)->create();
        $hisBrother=\App\Models\Square::factory()->for($question)->create(['brother'=>$firstSquare->id]);
        $firstSquare->brother=$hisBrother->id;
        $firstSquare->save();
        }
        $question->tests()->attach($test);
        }
        for($i=0;$i<50;$i++)
        {
         $question=\App\Models\Question::factory()->create(['type'=>'order']);
         for($a=0;$a<4; $a++)
         {
        \App\Models\Square::factory()->for($question)->create(['order'=>$a+1]);
        
         }
         $question->tests()->attach($test);
         }
        //Second test - custom
        $customTest=Test::factory()->create(['name' =>'Test Test', 'custom'=>true]);
       for($i=0;$i<200;$i++)
       {
        $question=\App\Models\Question::factory()->create(['custom'=>true]);
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($customTest);
        }
        $user->tests()->attach($customTest);
        $user->tests()->attach($test);


        //Generated Test
        $GeneratedTest=GeneratedTest::factory()->for($user)->for($test)->create();
        $questions=$test->questions()->inRandomOrder()->limit(150)->get();
        foreach($questions as $question)
        {
            $question->generatedQuestions()->create(['generated_test_id'=>$GeneratedTest->id]);
        }
    }
}
