<?php

namespace Paint\Format;

use Paint\Exception\CapabilityException;

class WebP implements FormatInterface
{
	/**
     * {@inheritdoc}
     */
	public function generate($output, $outputPath = null)
	{
		// imagewebp() is only available since PHP 5.5
		if (!function_exists('imagewebp')) {
			throw new CapabilityException('WebP writing is not supported.');
		}

		imagewebp($output, $outputPath);
	}
}
