<?php

namespace Paint\Format;

class PNG implements FormatInterface
{
	public $quality = 0;

	public $filters;

	/**
	 * Constructor
	 *
	 * @param int $quality PNG Compression level: from 0 (no compression) to 9.
	 * @param int $filter  Allows reducing the PNG file size. It is a bitmask field which may be set to any combination of the PNG_FILTER_XXX constants. PNG_NO_FILTER or PNG_ALL_FILTERS may also be used to respectively disable or activate all filters.
	 **/
	public function __construct($quality = 0, $filters = null)
	{
		$this->quality = min(9, abs((int) $quality));
		$this->filters = $filters;
	}

	public function generate($output, $outputPath = null)
	{
		imagepng($output, $outputPath, $this->quality, $this->filters);
	}
}
