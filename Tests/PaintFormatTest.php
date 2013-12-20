<?php

namespace Paint\Tests;

use Paint\Color;
use Paint\Paint;
use Paint\Utils;

class PaintFormatTest extends \PHPUnit_Framework_TestCase
{
	public function setUpBeforeClass()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	public function testInvalidOutputFormat()
	{
		$this->setExpectedException('PHPUnit_Framework_Error');

		$paint = Paint::create();
		$paint->generate('unknow');
	}

	public function testGenerateGIF()
	{
		$file = 'tests/generated/output.gif';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\GIF());

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateJPEG()
	{
		$file = 'tests/generated/output.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG(60));

		$this->assertEquals(true, file_exists($file));
	}

	public function testGeneratePNG()
	{
		$file = 'tests/generated/output.png';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\PNG(2));

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateWBMP()
	{
		$file = 'tests/generated/output.wbmp';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\WBMP());

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

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\WebP());

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateXBM()
	{
		$file = 'tests/generated/output.xbm';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->output($file);
		$paint->generate(new \Paint\Format\XBM());

		$this->assertEquals(true, file_exists($file));
	}

}