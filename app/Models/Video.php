<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Processors\Video\VideoProcessor;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['completed_at', 'notification_received_at'];
    protected $appends = ['video_url', 'thumb_url'];

    public function getNotificationUrlAttribute()
    {
        return env('PIANOLIT_NOTIFICATION_URL');
    }

    public function getVideoUrlAttribute()
    {
        return \Storage::disk('gcs')->url($this->video_path);
    }

    public function getThumbUrlAttribute()
    {
        return \Storage::disk('gcs')->url($this->thumb_path);
    }

    public function getOriginalSizeMbAttribute()
    {
        return round($this->original_size / 1000000, 1) . 'mb';
    }

    public function getCompressedSizeMbAttribute()
    {
        return round($this->compressed_size / 1000000, 1) . 'mb';
    }

    public function getSizeDecreasePercentageAttribute()
    {
        $diff = $this->original_size - $this->compressed_size;
        $percentage = round(($diff * 100) / $this->original_size) * -1;

        return $percentage > 0 ? '+'.$percentage.'%' : $percentage.'%';
    }

    public function getProcessingTimeAttribute()
    {
        if ($this->completed())
            return gmdate('i:s', $this->completed_at->diffInSeconds($this->created_at));
    }

    public function getOriginIconAttribute()
    {
        switch ($this->origin) {
            case 'webapp':
                return 'fas fa-laptop';
                break;

            case 'ios':
                return 'fab fa-apple';
                break;

            case 'android':
                return 'fas fa-mobile';
                break;

            default:
                return $this->origin ?? 'question';
                break;
        }
    }

    public function sendNotification()
    {
        if (! in_array($this->origin, ['webapp', 'ios']))
            return nulll;

        $response = \Http::post($this->notification_url, ['video' => $this->toArray()]);

        if ($response->successful())
            $this->update(['notification_received_at' => now()]);

        return $response;
    }

    public function scopeFromTag($query, $tag)
    {
        $pieces = explode(':', $tag);

        $class = $pieces[0];
        $id = $pieces[1];

        if ($class != get_class($this))
            abort(404, 'This job was not for a Video');

        return $query->find($id);
    }

    public function scopeTemporary($query, UploadedFile $file, array $request)
    {
        return $query->create([
            'origin' => $request['origin'],
            'piece_id' => $request['piece_id'],
            'user_id' => $request['user_id'],
            'user_email' => $request['email'],
            'temp_path' => \Storage::disk('public')->put('temporary', $file),
            'original_size' => $file->getSize()
        ]);
    }

    public function completed()
    {
        return (bool) $this->completed_at;
    }

    public function finish(VideoProcessor $processor)
    {
        return $this->update([
            'video_path' => $processor->path()->video(),
            'thumb_path' => $processor->path()->thumbnail(),
            'compressed_size' => \Storage::disk('gcs')->size($processor->path()->video()),
            'mimeType' => \Storage::disk('gcs')->mimeType($processor->path()->video()),
            'completed_at' => now()
        ]);        
    }
}
