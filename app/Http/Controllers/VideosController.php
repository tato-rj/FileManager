<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessVideo;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->paginate(6);

        return view('welcome', compact('videos'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'file' => 'required|mimes:mp4,mov,avi,webm,wmv',
            'email' => 'required|email',
            'user_id' => 'required|integer',
            'piece_id' => 'required|integer',
            'origin' => 'required|string'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validator->errors(), 422);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive();

        if ($fileReceived->isFinished()) {
            $file = $fileReceived->getFile();

            ProcessVideo::dispatch(
                Video::temporary($file, $request->toArray())
            );

            unlink($file->getPathname());

            return response(200);
        }

        return [
            'done' => $fileReceived->handler()->getPercentageDone(),
            'status' => true
        ];

        // ProcessVideo::dispatch(
        //     Video::temporary($request->file('video'), $request->toArray())
        // );

        // return back();
    }

    public function destroy(Request $request)
    {
        $video = Video::where(['user_id' => $request->user_id, 'piece_id' => $request->piece_id])->firstOrFail();

        if ($video->temp_path && \Storage::disk('public')->exists($video->temp_path))
            \Storage::disk('public')->delete($video->temp_path);

        if ($video->video_path && \Storage::disk('gcs')->exists($video->video_path))
            \Storage::disk('gcs')->delete($video->video_path);

        if ($video->thumb_path && \Storage::disk('gcs')->exists($video->thumb_path))
            \Storage::disk('gcs')->delete($video->thumb_path);

        $video->delete();

        return $request->wantsJson() ? response(200) : back();
    }
}
