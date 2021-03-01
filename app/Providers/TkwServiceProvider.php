<?php

namespace App\Providers;

use App\Mail\Mailer;
use App\Mail\MailerInterface;
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
