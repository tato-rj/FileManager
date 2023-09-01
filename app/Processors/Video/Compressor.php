<?php

namespace App\Processors\Video;

use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;

class Compressor
{
	protected $quality = 250;
	protected $dimensions = [480, 360];

	public function fire(VideoProcessor $processor)
	{
		$processor->rawFile()
				  ->addFilter(function ($filters) {
					$filters->resize(new Dimension($this->dimensions[0], $this->dimensions[1]));
				  })
				  ->export()
				  ->toDisk('gcs')
				  ->inFormat(
				  	(new X264)->setKiloBitrate($this->quality)
				  )
				  ->save(
					$processor->path()->video()
				  );
	}
}