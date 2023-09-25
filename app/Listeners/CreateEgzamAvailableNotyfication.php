<?php

namespace App\Listeners;

use App\Events\EgzamStarted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateEgzamAvailableNotyfication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(EgzamStarted $event): void
    {
        $test=$event->test;
        $team=$test->team()->firstOrFail();
        foreach($team->users as $user)
        {
            if(!$user->pivot->is_teacher)
            {
                $test->notyfications()->create([
                    'name'=>'Nowy egzamin jest dostępny.',
                    'description'=>'Egzamin o nazwie: '.$test->name.'dla grupy '.$team->name.' rozpoczął się',
                    'type'=>'egzamAvailable',
                    'user_id'=>$user->id
                ]);
            }
        }
    }
}
