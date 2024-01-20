<?php

namespace App\Providers;

use App\Models\CustomTest;
use App\Models\Test;
use App\Policies\CustomTestPolicy;
use App\Policies\TestPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        CustomTest::class => CustomTestPolicy::class,
        Test::class => TestPolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $frontend= str_replace(config('app.url'),config('app.frontend_url').'/user', $url);
            return (new MailMessage)
                ->subject('Zweryfikuj adress e-mail')
                ->line('Nacisnij przycisk, aby zweryfikować swój adres e-mail.')
                ->line('Pamiętaj, że musisz być zalogowany w oknie przeglądarki, żeby weryfikacja się powiodła.')
                ->action('Zweryfikuj adres email', $frontend);
        });
    }
}
