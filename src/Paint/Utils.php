<?php

namespace Paint;

class Utils
{
	/**
	 * validate an RGB color composant
	 *
	 * @param int $color Integers between 0 and 255 or hexadecimals between 0x00 and 0xFF
	 * @return int Valid color value between 0 and 255
	 **/
	public static function validColor($color)
	{
		// convert hexa to decimal
		if (is_string($color)) {
			$color = hexdec($color);
		}

		return min(abs((int) $color), 255);
	}


	/**
	 * validate an alpha composant
	 *
	 * @param int $color Integers between 0 and 127 or hexadecimals between 0x00 and 0xFF
	 * @return int Valid alpha value between 0 and 127
	 **/
	public static function validAlpha($color)
	{
		// convert hexa to decimal
		if (is_string($color)) {
			$color = hexdec($color);
		}

		return min(abs((int) $color), 127);
	}
}
