<?php

namespace Paint;

use Paint\Utils;

class Color
{
    public $red;

    public $green;

    public $blue;

    public $alpha;

    public $color;

    /**
     * Allocate a color for an image
     *
     * @param int $red   Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $green Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $blue  Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $alpha integer between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
     **/
    public function __construct($red, $green, $blue, $alpha = null)
    {
        $this->red = Utils::validColor($red);
        $this->green = Utils::validColor($green);
        $this->blue = Utils::validColor($blue);

        if (!is_null($alpha)) {
            $this->alpha = Utils::validAlpha($alpha);
            $this->color = imagecolorallocatealpha(imagecreatetruecolor(1, 1), $this->red, $this->green, $this->blue, $this->alpha);
        }
        else {
            $this->color = imagecolorallocate(imagecreatetruecolor(1, 1), $this->red, $this->green, $this->blue);
        }
    }

    /**
     * Get the color ressource
     *
     * @return int Color identifier representing the color composed of the given RGB components.
     **/
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Initilize a color for an image
     *
     * @param int $red   Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $green Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $blue  Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
     * @param int $alpha integer between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
     * @return int Color identifier representing the color composed of the given RGB components.
     **/
    public static function get($red = 0, $green = 0, $blue = 0, $alpha = 0)
    {
        $color = new self($red, $green, $blue, $alpha);
        return $color->getColor();
    }
}
