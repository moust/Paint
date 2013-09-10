<?php

namespace Paint\Filter;

class FilterNegate implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_NEGATE))
		{
			throw new FilterException('Fail to apply negate filter.');
		}
	}
}