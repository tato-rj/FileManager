<?php

namespace App\Processors\Video;

use App\Models\Video;

class VideoProcessor
{
	public $temp_path, $filename, $withThumbnail;

	public function __construct(Video $video)
	{
		$this->video = $video;
		$this->filename = basename($video->temp_path);
		$this->withThumbnail = false;
	}

	public function rawFile()
	{
		return \FFMpeg::fromDisk('public')->open($this->video->temp_path);
	}

	public function path()
	{
		return new Path($this);
	}

	public function run()
	{
		(new Compressor)->fire($this);

		if ($this->withThumbnail)
			(new Thumbnail)->create($this);

		$this->cleanup();

		return $this;
	}

	public function withThumbnail()
	{
		$this->withThumbnail = true;

        return $this;
	}

	public function cleanup()
	{
		\Storage::disk('public')->delete($this->video->temp_path);

		$this->video->update(['temp_path' => null]);
		
		\FFMpeg::cleanupTemporaryFiles();
	}
}