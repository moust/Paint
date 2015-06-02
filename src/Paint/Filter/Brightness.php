<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Brightness implements FilterInterface
{
	/**
	 * @param int $arg1 Brightness level (-255 = min brightness, 0 = no change, +255 = max brightness)
	 * @return void
	 **/
	public function __construct($arg1)
	{
		$this->arg1 = max(-255, min(255, (int) $arg1));
	}

	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_BRIGHTNESS, $this->arg1))
		{
			throw new FilterException('Fail to apply brightness filter.');
		}
	}
}
