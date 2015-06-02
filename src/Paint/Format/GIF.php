<?php

namespace Paint\Format;

class GIF implements FormatInterface
{
	/**
     * {@inheritdoc}
     */
	public function generate($output, $outputPath = null)
	{
		imagegif($output, $outputPath);
	}
}
