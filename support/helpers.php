<?php

function storage($path)
{
	if ($path)
		return asset(\Storage::url($path));

	return null;
}

function bugsnag()
{
	//
}