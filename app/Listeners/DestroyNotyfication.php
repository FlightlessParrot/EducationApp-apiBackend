<?php

namespace App\Listeners;

use App\Events\NotyficationExpired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyNotyfication
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
    public function handle(NotyficationExpired $event): void
    {
        $event->notyfication->delete();
    }
}
