<?php

namespace Database\Seeders;

use App\Models\UserAdress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->has(UserAdress::factory())->create();

        \App\Models\User::factory()->has(UserAdress::factory())->create([
           'name' => 'Test User',
           'email' => 'test@example.com',
           'password'=>Hash::make('TestPassword'),
           'role'=>'premium'
        ]);

     \App\Models\User::factory()->has(UserAdress::factory())->create(['email'=> 'user@example.com', 'password'=>Hash::make('TestPassword')]);
    \App\Models\User::factory()->has(UserAdress::factory())->create(['email'=> 'admin@example.com', 'password'=>Hash::make('TestPassword'),'role'=>'admin']);
    } 
    
}
