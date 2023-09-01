<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;

class VideoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::after(function (JobProcessed $event) {
            // \App\Models\Video::first()->update(['user_email' => 'it@works']);
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });
    }
}
