<?php

namespace App\Listeners;

use App\Events\PaymentStatusChange;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddSubscriptionAndSendMail
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
    public function handle(PaymentStatusChange $event): void
    {
       if($event->payment->status==='CONFIRMED')
       {
        $subscription=Subscription::find($event->payment->subscription_id);
        $user=$event->payment->user()->first();
        $user->subscriptions()->attach($subscription,['expiration_date'=>$subscription->license_duration]);
       }
    }
}
