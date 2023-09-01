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
    protected $dates = ['completed_at'];
    protected $appends = ['video_url', 'thumb_url'];

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

    public function scopeTemporary($query, UploadedFile $file, array $request)
    {
        return $query->create([
            'user_id' => $request['id'],
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

    public function webhook()
    {
        return $this->toArray();
    }
}
