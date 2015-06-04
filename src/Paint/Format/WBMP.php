<?php

namespace Paint\Format;

use Paint\Color;
use Paint\Exception\CapabilityException;

class WBMP implements FormatInterface
{
	public $foreground;

	/**
	 * Constructor
	 *
	 * @param int $red JPEG Compression level: from 0 to 100 (no compression).
	 **/
	public function __construct(Color $foreground = null)
	{
		if (!function_exists('imagewbmp')) {
			throw new CapabilityException('WBMP writing is not supported.');
		}

		if (!is_null($foreground)) {
			$this->foreground = $foreground;
		}
	}

	/**
     * {@inheritdoc}
     */
	public function generate($output, $outputPath = null)
	{
		// WBMP foreground seem doesn't work...
		if (!is_null($this->foreground)) {
			imagewbmp($output, $outputPath, $this->foreground->getColor());
		}
		else {
			imagewbmp($output, $outputPath);
		}
	}
}
