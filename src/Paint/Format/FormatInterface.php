<?php

namespace Paint\Format;

interface FormatInterface
{
	public function generate($output, $outputPath = null);
}
