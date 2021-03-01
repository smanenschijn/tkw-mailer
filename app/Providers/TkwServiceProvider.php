<?php

namespace App\Providers;

use App\Mail\Mailer;
use app\Mail\MailerInterface;
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
