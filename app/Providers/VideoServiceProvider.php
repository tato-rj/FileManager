<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use App\Models\Video;

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
            try {
                $video = Video::fromTag(
                    $event->job->payload()['tags'][0]
                );

                \Log::debug('Sending notification back to PianoLIT for video ID: '.$video->id);

                $video->sendJobProcessedNotification();
            } catch (Exception $e) {
                bugsnag();
            }
        });
    }
}
