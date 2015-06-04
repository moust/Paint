<?php

namespace Paint\Format;

interface FormatInterface
{
    /**
     * Creates the image file in filename from the image
     *
     * @param resource An image resource.
     * @param string The path to save the file to.
     * @return void
     **/
    public function generate($output, $outputPath = null);
}
