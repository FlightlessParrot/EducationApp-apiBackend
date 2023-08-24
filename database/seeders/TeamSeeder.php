<?php

namespace Database\Seeders;

use App\Models\GeneratedQuestion;
use App\Models\Team;
use App\Models\Test;
use App\Models\User;
use App\Models\OpenAnswer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::where('email', 'test@example.com')->first();
        $team=Team::factory()->has(User::factory(10))->create();
        
        
        $team->users()->attach($user,['is_teacher'=>true]); 
        $test=null;
        for($i=0;$i<2;$i++)
        {
        $test=Test::factory()->create(['name' =>fake()->sentence(), 'role'=>'egzam']);
        $team->tests()->attach($test);

       for($i=0;$i<3;$i++)
       {
        $question=\App\Models\Question::factory()->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        for($i=0;$i<3;$i++)
       {
        $question=\App\Models\Question::factory()->create(['type'=>'many-answers']);
        \App\Models\Answer::factory()->for($question)->count(2)->create();
        \App\Models\Answer::factory()->for($question)->count(2)->create([
            'correct'=>true
        ]);
        $question->tests()->attach($test);
        }
        for($i=0;$i<2;$i++)
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
        for($i=0;$i<2;$i++)
        {
         $question=\App\Models\Question::factory()->create(['type'=>'order']);
         for($a=0;$a<4; $a++)
         {
        \App\Models\Square::factory()->for($question)->create(['order'=>$a+1]);
        
         }
         $question->tests()->attach($test);
         }
        }
        foreach($team->users as $user)
        {
            
            $generatedTest=$user->generatedTests()->create(
            [
            'test_id'=>$test,'duration'=>((string)rand(0,10)).':00', 
            'egzam'=>true, 'gandalf'=>true, 
            'questions_number'=>10, 'solved'=>true, 'test_id'=>$test->id
             ]);
            foreach($test->questions as $question)
            {
                $generatedTest->generatedQuestions()->create(['question_id'=>$question->id,'answer'=>(bool)round(rand(0,1)), 'relevant'=>true]);
            }
            $question=\App\Models\Question::factory()->create(['type'=>'open']);
            $test->questions()->attach($question);
            $openQuestion=GeneratedQuestion::create(['generated_test_id'=>$generatedTest->id, 'question_id'=>$question->id]);

            $openAnswer=OpenAnswer::factory()->create(['generated_question_id'=>$openQuestion->id]);
        }
        foreach($team->users as $user)
        {
            
            $generatedTest=$user->generatedTests()->create(
            [
            'test_id'=>$test,'duration'=>((string)rand(0,10)).':00', 
            'egzam'=>true, 'gandalf'=>true, 
            'questions_number'=>10, 'solved'=>true, 'test_id'=>$test->id
             ]);
            foreach($test->questions as $question)
            {
                $generatedTest->generatedQuestions()->create(['question_id'=>$question->id,'answer'=>(bool)round(rand(0,1)), 'relevant'=>true]);
            }
            $question=\App\Models\Question::factory()->create(['type'=>'open']);
            $test->questions()->attach($question);
            $openQuestion=GeneratedQuestion::create(['generated_test_id'=>$generatedTest->id, 'question_id'=>$question->id, 'relevant'=>false]);

            $openAnswer=OpenAnswer::factory()->create(['generated_question_id'=>$openQuestion->id]);
        }
    }
}
