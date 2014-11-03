<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class SelectiveBlur implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_SELECTIVE_BLUR))
		{
			throw new FilterException('Fail to apply selective blur filter.');
		}
	}
}
