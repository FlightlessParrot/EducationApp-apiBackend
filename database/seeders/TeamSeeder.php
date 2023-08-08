<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
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
    }
}
