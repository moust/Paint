<?php

namespace Paint\Filter;

class FilterGaussianBlur implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR))
		{
			throw new FilterException('Fail to apply gaussian blur filter.');
		}
	}
}