<?php

namespace Database\Seeders;

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
     
    
        $test=\App\Models\Test::factory()->has(User::factory()->state([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>Hash::make('TestPassword')
       ]))->create();
        $question=\App\Models\Question::factory()->for($test)->create();
        \App\Models\Answer::factory()->for($question)->count(3)->create();
        \App\Models\Answer::factory()->for($question)->create([
            'correct'=>true
        ]);
        
        
    }
}
