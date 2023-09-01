<?php

namespace App\Processors\Video;

class Path
{
	protected $namespace = 'performances';

	public function __construct(VideoProcessor $processor)
	{
		$this->processor = $processor;
	}

	public function thumbnail()
	{
		$path = str_replace(pathinfo($this->processor->filename, PATHINFO_EXTENSION), 'jpg', $this->processor->filename);

		return $this->namespace . '/test@email.com/' . $path;
	}

	public function video()
	{
		return $this->namespace . '/test@email.com/' . $this->processor->filename;
	}
}