<?php

namespace App\Providers;

use App\Events\MessageCreated;
use App\Events\MessageSent;
use App\Listeners\LogEmailSent;
use App\Listeners\SendMessageWithServices;
use App\Listeners\UpdateEmailStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageCreated::class => [
            SendMessageWithServices::class
        ],
        MessageSent::class => [
            LogEmailSent::class,
            UpdateEmailStatus::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
