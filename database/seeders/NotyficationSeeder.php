<?php

namespace Database\Seeders;

use App\Models\GeneratedQuestion;
use App\Models\Notyfication;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotyficationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::where('email', 'test@example.com')->first();
        foreach(GeneratedQuestion::where('relevant',false)->get() as $generatedQuestion)
        {
            if($generatedQuestion->question()->first()->type!=='open')
            {
                continue;
            }
            $generatedTest=$generatedQuestion->generatedTest()->first();
            $test=$generatedTest->test()->first();
            $notyfication=new Notyfication(['user_id'=>$user->id, 
            'name'=>'Niesprawdzone odpowiedzi do pytań otwartych','description'=>'Sprawdź odpowiedzi udzielone na pytania otwarte w egzaminie "'.$test->name.'"',
            'type'=>'openQuestionToCheck']);
            if($test->notyfications()->where('type','openQuestionToCheck')->first()==null)
            {
               $test->notyfications()->save($notyfication);  
            }
            
        }
        
    }
}
