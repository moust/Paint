<?php

namespace Paint\Format;

use Paint\Color;

class XBM implements FormatInterface
{
	public $foreground;

	/**
	 * Constructor
	 *
	 * @param int $red JPEG Compression level: from 0 to 100 (no compression).
	 **/
	public function __construct(Color $foreground = null)
	{
		if (!is_null($foreground)) {
			$this->foreground = $foreground;
		}
	}

	public function generate($output, $outputPath = null)
	{
		// FIXME: WBMP foreground seem doesn't work...
		if (!is_null($this->foreground)) {
			imagexbm($output, $outputPath, $this->foreground->getColor());
		}
		else {
			imagexbm($output, $outputPath);
		}
	}
}