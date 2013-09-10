<?php

namespace Paint\Format;

class JPEG implements FormatInterface
{
	public $quality = 100;

	/**
	 * Constructor
	 *
	 * @param int $quality JPEG Compression level: from 0 to 100 (no compression).
	 **/
	public function __construct($quality = 100)
	{
		$this->quality = min(100, abs((int) $quality));
	}

	public function generate($output, $outputPath = null)
	{
		imagejpeg($output, $outputPath, $this->quality);
	}
}