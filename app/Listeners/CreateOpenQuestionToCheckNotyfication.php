<?php

namespace App\Listeners;

use App\Events\OpenAnswersWritten;
use App\Models\Notyfication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateOpenQuestionToCheckNotyfication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OpenAnswersWritten $event): void
    {
        $test=$event->test;
        $team=$test->teams()->firstOrFail();
        $user=$team->users()->wherePivot('is_teacher',true)->firstOrFail();
        $notyfication=new Notyfication(['user_id'=>$user->id, 
            'name'=>'Niesprawdzone odpowiedzi do pytań otwartych','description'=>'Sprawdź odpowiedzi udzielone na pytania otwarte w egzaminie "'.$test->name.'"',
            'type'=>'openQuestionToCheck']);
            if($test->notyfications()->where('type','openQuestionToCheck')->first()==null)
            {
               $test->notyfications()->save($notyfication);  
            }

    }
}
