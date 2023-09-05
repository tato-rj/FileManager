<?php

namespace App\Processors\Video;

class Compressor
{
	public function fire(VideoProcessor $processor)
	{
		$dimensions = (new Dimensions($processor))->get();

		$processor->rawFile()
				  ->resize($dimensions->height, $dimensions->width)
				  ->export()
				  ->toDisk('gcs')
				  ->inFormat((new Format)->getLowQuality())
				  ->save($processor->path()->video());
	}
}