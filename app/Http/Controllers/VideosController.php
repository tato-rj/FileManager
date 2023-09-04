<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessVideo;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->paginate(6);

        return view('welcome', compact('videos'));
    }

    public function upload(Request $request)
    {
        if (! \DB::table('personal_access_tokens')->where('name', $request->secret)->exists())
            throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to do this');

        $validator = Validator::make($request->all(), [
            'video' => 'required|mimes:mp4,mov,avi,webm,wmv',
            'email' => 'required|email',
            'user_id' => 'required|integer',
            'piece_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validator->errors(), 422);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

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
