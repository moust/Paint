<?php

namespace Paint\Transformation;

interface TransformationInterface
{
	/**
	 * Apply image transformation
	 *
	 * @param ressource An image resource.
	 */
	public function apply(&$image);
}
