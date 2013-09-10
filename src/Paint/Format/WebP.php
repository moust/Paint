<?php

namespace Paint\Format;

class WebP implements FormatInterface
{
	public function generate($output, $outputPath = null)
	{
		imagewebp($output, $outputPath);
	}
}