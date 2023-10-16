<?php

namespace App\Providers;
use App\Events\NotyficationExpired;
use App\Listeners\DestroyNotyfication;
use App\Events\OpenAnswersWritten;
use App\Listeners\CreateOpenQuestionToCheckNotyfication;
use App\Events\EgzamStarted;
use App\Listeners\AddSubscriptionAndSendMail;
use App\Events\PaymentStatusChange;
use App\Listeners\CreateEgzamAvailableNotyfication;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OpenAnswersWritten::class => [
            CreateOpenQuestionToCheckNotyfication::class
        ],
        EgzamStarted::class=>[
            CreateEgzamAvailableNotyfication::class
        ],
        NotyficationExpired::class=>[
            DestroyNotyfication::class
        ],
        PaymentStatusChange::class=>[
            AddSubscriptionAndSendMail::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
