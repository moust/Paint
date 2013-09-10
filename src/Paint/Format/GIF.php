<?php

namespace Paint\Format;

class GIF implements FormatInterface
{
	public function generate($output, $outputPath = null)
	{
		imagegif($output, $outputPath);
	}
}