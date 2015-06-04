<?php

namespace Paint\Tests;

use Paint\Color;
use Paint\Image;
use Paint\Format;
use Paint\Utils;

class FormatTest extends \PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	public function testInvalidOutputFormat()
	{
		$this->setExpectedException('PHPUnit_Framework_Error');

		$image = new Image('tests/carlos.jpeg');
		$image->generate('unknow');
	}

	public function testGenerateGIF()
	{
		if (!function_exists('imagegif')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.gif';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\GIF(), $file);

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateJPEG()
	{
		if (!function_exists('imagejpeg')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\JPEG(60), $file);

		$this->assertEquals(true, file_exists($file));
	}

	public function testGeneratePNG()
	{
		if (!function_exists('imagepng')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.png';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\PNG(2), $file);

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateWBMP()
	{
		if (!function_exists('imagewbmp')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.wbmp';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\WBMP(), $file);

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateWebP()
	{
		// imagewebp() is only available since PHP 5.5
		if (!function_exists('imagewebp')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.webp';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\WebP(), $file);

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateXBM()
	{
		if (!function_exists('imagexbm')) {
			$this->setExpectedException('Paint\Exception\CapabilityException');
		}

		$file = 'tests/generated/output.xbm';

		if (file_exists($file)) {
			unlink($file);
		}

		$image = new Image('tests/carlos.jpeg');
		$image->generate(new Format\XBM(), $file);

		$this->assertEquals(true, file_exists($file));
	}

}
