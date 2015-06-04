<?php

namespace Paint\Format;

use Paint\Exception\CapabilityException;

class GIF implements FormatInterface
{
	public function __construct()
	{
		if (!function_exists('imagegif')) {
			throw new CapabilityException('GIF is not supported.');
		}
	}

	/**
     * {@inheritdoc}
     */
	public function generate($output, $outputPath = null)
	{
		imagegif($output, $outputPath);
	}
}
