<?php

namespace Paint\Tests;

use Paint\Paint;
use Paint\Utils;
use Paint\Color;

class PaintTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	protected function assertImageColorEquals($img, $red, $green, $blue)
	{
		$rgb = imagecolorsforindex($img, imagecolorat($img, 4, 4));
		$this->assertEquals($red, $rgb['red']);
		$this->assertEquals($green, $rgb['green']);
		$this->assertEquals($blue, $rgb['blue']);
	}

	public function testCreate()
	{
		$paint = Paint::create();
		$this->assertInstanceOf('Paint\Paint', Paint::create());
	}

	public function testValidColor()
	{
		$this->assertEquals(0, Utils::validColor(0));
		$this->assertEquals(255, Utils::validColor(255));
		$this->assertEquals(255, Utils::validColor('255'));
		$this->assertEquals(0, Utils::validColor(0x00));
		$this->assertEquals(255, Utils::validColor(0xFF));
		$this->assertEquals(255, Utils::validColor('0xFF'));
	}

	public function testColor()
	{
		$this->assertEquals(imagecolorallocate(imagecreatetruecolor(1, 1), 255, 127, 0), Color::get(255, 127, 0));
		$this->assertEquals(imagecolorallocate(imagecreatetruecolor(1, 1), 0x88, 0x00, 0xFF), Color::get(0x88, 0x00, 0xFF));
		$this->assertEquals(imagecolorallocatealpha(imagecreatetruecolor(1, 1), 255, 127, 0, 127), Color::get(255, 127, 0, 127));
	}

	public function testInput()
	{
		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
	}

	public function testInvalidInput()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->input('tests/undefined.jpeg');

		$paint = Paint::create();
		$paint->input('tests/');
	}

	public function testOutput()
	{
		$paint = Paint::create();
		$paint->output('tests/generated/carlos-output.jpeg');
	}

	public function testOutputNotWritable()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->output('tests/generated/undefined/carlos.jpeg');
	}

	public function testValidOutputFormat()
	{
		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		
		ob_start();
		$paint->generate(new \Paint\Format\JPEG());
		$paint->generate(new \Paint\Format\GIF());
		$paint->generate(new \Paint\Format\PNG());
		$paint->generate(new \Paint\Format\WBMP());
		ob_end_clean();
	}

	public function testInvalidOutputFormat()
	{
		$this->setExpectedException('PHPUnit_Framework_Error');

		$paint = Paint::create();
		$paint->generate('unknow');
	}

	public function testGenerateJPEG()
	{
		$file = 'tests/generated/output.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
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
		$paint->setOutputSize(100, 100);
		$paint->output($file);
		$paint->generate(new \Paint\Format\PNG(2));

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateGIF()
	{
		$file = 'tests/generated/output.gif';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->output($file);
		$paint->generate(new \Paint\Format\GIF());

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateWBMP()
	{
		$file = 'tests/generated/output.wbmp';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->output($file);
		$paint->generate(new \Paint\Format\WBMP());

		$this->assertEquals(true, file_exists($file));
	}

	// TODO : WBMP foreground seem doesn't work...
	// public function testGenerateWBMPForeground()
	// {
	// 	$file = 'tests/output-foreground.wbmp';
		
	// 	if (file_exists($file)) {
	// 		unlink($file);
	// 	}

	// 	$paint = Paint::create();
	// 	$paint->setOutputSize(100, 100);
	// 	$paint->output($file);
	// 	$paint->generate(new \Paint\Format\WBMP(new Color(255, 0, 0)));

	// 	$this->assertEquals(true, file_exists($file));

	// 	$img = imagecreatefromwbmp($file);
	// 	$this->assertImageColorEquals($img, 255, 0, 0);
	// }

	public function testColorFill()
	{
		$file = 'tests/generated/output-red.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->colorFill(new Color(255, 0, 0));
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));

		$img = imagecreatefromjpeg($file);
		$this->assertImageColorEquals($img, 254, 0, 0);
	}

	public function testResizeSmaller()
	{
		$file = 'tests/generated/resize-smaller.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(300, 300);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testResizeBigger()
	{
		$file = 'tests/generated/resize-bigger.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(1024, 768, Paint::RESIZE_FIT);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testCrop()
	{
		$file = 'tests/generated/resize-crop.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(300, 300, Paint::RESIZE_CROP);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

}