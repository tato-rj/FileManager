<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class WebhookController extends Controller
{
    public function resend(Video $video)
    {
        $response = $video->sendNotification();

        if ($response->ok())
            return back()->with('success', 'The notification was successfully received');

        return back()->with('error', 'Something went wrong. STATUS: '.$response->status());
    }
}
