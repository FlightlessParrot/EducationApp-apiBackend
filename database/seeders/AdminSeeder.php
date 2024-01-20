<?php

namespace Database\Seeders;

use App\Models\UserAdress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->has(UserAdress::factory())->create(['name'=>'Gracjan Różański','email'=> 'gracjanrozanski95@gmail.com', 'password'=>Hash::make('LearnMed2023!'),'role'=>'admin']);
    }
}
