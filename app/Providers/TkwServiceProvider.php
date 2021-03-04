<?php

namespace App\Providers;

use App\Fallback\CircuitBreaker;
use App\Fallback\CircuitBreakerInterface;
use App\Mail\Mailer;
use App\Mail\MailerInterface;
use App\Mail\Services\MailJet;
use App\Mail\Services\MailJetInterface;
use App\Mail\Services\SendGrid;
use App\Mail\Services\SendGridInterface;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\EmailRepository;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class TkwServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MailerInterface::class, Mailer::class);
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
        $this->app->bind(SendGridInterface::class, SendGrid::class);
        $this->app->bind(MailJetInterface::class, MailJet::class);
        $this->app->bind(CircuitBreakerInterface::class, CircuitBreaker::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
