<?php

namespace Paint\Filter;

class Pixelate implements FilterInterface
{
	/**
	 * @param int $arg1 : Block size in pixels
	 * @param bool $arg2 : Whether to use advanced pixelation effect or not (defaults to FALSE)
	 * @return void
	 **/
	public function __construct($arg1, $arg2)
	{
		$this->arg1 = (int) $arg1;
		$this->arg2 = (bool) $arg2;
	}
	
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_PIXELATE, $this->arg1, $this->arg2))
		{
			throw new FilterException('Fail to apply pixelate filter.');
		}
	}
}