<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Edgedetect implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_EDGEDETECT))
		{
			throw new FilterException('Fail to apply edgedetect filter.');
		}
	}
}