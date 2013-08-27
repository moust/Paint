<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

interface FilterInterface
{
	public function apply($image);
}