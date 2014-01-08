<?php

namespace Paint\Format;

use Paint\Color;
use Paint\Format\CapabilityException;

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
		if (!function_exists('imagexbm')) {
			throw new CapabilityException('XBM writing is not supported.');
		}

		// XBM foreground seem doesn't work...
		if (!is_null($this->foreground)) {
			$this->imagexbm($output, $outputPath, $this->foreground->getColor());
		}
		else {
			$this->imagexbm($output, $outputPath);
		}
	}

	/**
	 * Fix imagexbm bug where the output stream is still sent to stdout (https://bugs.php.net/bug.php?id=66339)
	 *
	 * @return void
	 **/
	protected function imagexbm($output, $outputPath = null, $foreground = null)
	{
		if ($outputPath) {
			ob_start();
			imagexbm($output, $outputPath, $foreground);
			$data = ob_get_contents();
			ob_end_clean();
			file_put_contents($outputPath, $data, LOCK_EX);
		}
		else {
			imagexbm($output, $outputPath, $foreground);
		} 
	}
}