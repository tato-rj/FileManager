<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessVideo;
use App\Models\Video;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->paginate(6);

        return view('welcome', compact('videos'));
    }

    public function upload(Request $request)
    {
        // if (\App::environment() == 'production') {
        //     if ($request->pin != env('FILEMANAGER_SECRET'))
        //         abort(404);
        // }

        // $request->validate([
        //     'video' => 'required|mimes:mp4,mov,avi,webm,wmv',
        //     'email' => 'required|email',
        //     'id' => 'required|integer'
        // ]);
dd('here');
        ProcessVideo::dispatch(
            Video::temporary($request->file('video'), $request->toArray())
        );

        return back();
    }

    public function webhook(Video $video)
    {
        return $video->webhook();
    }

    public function destroy(Video $video)
    {
        if ($video->temp_path && \Storage::disk('public')->exists($video->temp_path))
            \Storage::disk('public')->delete($video->temp_path);

        if ($video->video_path && \Storage::disk('gcs')->exists($video->video_path))
            \Storage::disk('gcs')->delete($video->video_path);

        if ($video->thumb_path && \Storage::disk('gcs')->exists($video->thumb_path))
            \Storage::disk('gcs')->delete($video->thumb_path);

        $video->delete();

        return back();
    }
}
