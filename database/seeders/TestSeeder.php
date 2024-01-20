<?php

namespace Database\Seeders;

use App\Models\GeneratedTest;
use App\Models\Question;
use App\Models\ShortAnswer;
use App\Models\Subscription;
use App\Models\Test;
use App\Models\User;
use DateTime;
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
        $date = new DateTime();
        $date->modify('+1 month');
        $user = User::where('email', 'test@example.com')->first();
        //first test
        $subscription = Subscription::factory()->create();
        $user->subscriptions()->attach($subscription, ['expiration_date' => $date]);
        $test = Test::factory()->create(['name' => 'General Test']);
        $test->subscriptions()->attach($subscription);
        for ($i = 0; $i < 50; $i++) {
            $question = \App\Models\Question::factory()->create();
            \App\Models\Answer::factory()->for($question)->count(3)->create();
            \App\Models\Answer::factory()->for($question)->create([
                'correct' => true
            ]);

            $question->tests()->attach($test);
        }
        for ($i = 0; $i < 50; $i++) {
            $question = \App\Models\Question::factory()->create(['type' => 'many-answers']);
            \App\Models\Answer::factory()->for($question)->count(2)->create();
            \App\Models\Answer::factory()->for($question)->count(2)->create([
                'correct' => true
            ]);
            $question->tests()->attach($test);
        }
        for ($i = 0; $i < 50; $i++) {
            $question = \App\Models\Question::factory()->create(['type' => 'pairs']);
            for ($a = 0; $a < 4; $a++) {
                $firstSquare = \App\Models\Square::factory()->for($question)->create();
                $hisBrother = \App\Models\Square::factory()->for($question)->create(['brother' => $firstSquare->id]);
                $firstSquare->brother = $hisBrother->id;
                $firstSquare->save();
            }
            $question->tests()->attach($test);
        }
        for ($i = 0; $i < 50; $i++) {
            $question = \App\Models\Question::factory()->create(['type' => 'order']);
            for ($a = 0; $a < 4; $a++) {
                \App\Models\Square::factory()->for($question)->create(['order' => $a + 1]);
            }
            $question->tests()->attach($test);
        }

        $questions = \App\Models\Question::factory()->has(ShortAnswer::factory())->count(50)->create(['type' => 'short-answer']);

        foreach($questions as $question)
        {
           $question->tests()->attach($test); 
        }
        

        //Second test - custom


        $customTest = Test::factory()->create(['name' => 'Custom Test', 'role' => 'custom', 'user_id' => $user->id]);
        $subscription->tests()->attach($customTest);
        for ($i = 0; $i < 200; $i++) {
            $question = \App\Models\Question::factory()->create(['custom' => true]);
            \App\Models\Answer::factory()->for($question)->count(3)->create();
            \App\Models\Answer::factory()->for($question)->create([
                'correct' => true
            ]);
            $question->tests()->attach($customTest);
        }





        //Generated Test
        $GeneratedTest = GeneratedTest::factory()->for($user)->for($test)->create();
        $questions = $test->questions()->inRandomOrder()->limit(150)->get();
        foreach ($questions as $question) {
            $question->generatedQuestions()->create(['generated_test_id' => $GeneratedTest->id]);
        }
    }
}
