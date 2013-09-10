<?php

namespace Paint\Filter;

class MeanRemoval implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_MEAN_REMOVAL))
		{
			throw new FilterException('Fail to apply mean removal filter.');
		}
	}
}