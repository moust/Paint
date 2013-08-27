<?php

namespace Paint\Filter;

class FilterContrast implements FilterInterface
{
	/**
	 * @param int $arg1 : Contrast level (-100 = max contrast, 0 = no change, +100 = min contrast)
	 * @return void
	 **/
	public function __construct($arg1)
	{
		$arg1 = (int) $arg1;
		$this->arg1 = max(-100, min(100, $arg1));
	}

	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_CONTRAST, $this->arg1))
		{
			throw new FilterException('Fail to apply contrast filter.');
		}
	}
}