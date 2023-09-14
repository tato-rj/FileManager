<?php

namespace App\Models\Traits;

trait CallsPianolit
{
    public function sendJobProcessedNotification()
    {
        $response = \Http::post($this->notification_url, ['video' => $this->toArray()]);

        if ($response->successful())
            $this->update(['notification_received_at' => now()]);

        return $response;
    }

    public function sendVideoDeletedNotification()
    {
        return \Http::delete($this->notification_url, ['video' => $this->toArray()]);
    }
}