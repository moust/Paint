<?php

namespace Paint\Tests;

use Paint\Paint;

class PaintTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	public function testCreate()
	{
		$paint = Paint::create();
		$this->assertInstanceOf('Paint\Paint', Paint::create());
	}

	public function testValidColor()
	{
		$this->assertEquals(0, Paint::validColor(0));
		$this->assertEquals(255, Paint::validColor(255));
		$this->assertEquals(255, Paint::validColor('255'));
		$this->assertEquals(0, Paint::validColor(0x00));
		$this->assertEquals(255, Paint::validColor(0xFF));
		$this->assertEquals(255, Paint::validColor('0xFF'));
	}

	public function testInput()
	{
		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
	}

	public function testInvalidInput()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->input('Tests/undefined.jpeg');

		$paint = Paint::create();
		$paint->input('Tests/');
	}

	public function testOutput()
	{
		$paint = Paint::create();
		$paint->output('Tests/carlos-output.jpeg');
	}

	public function testOutputExists()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Notice');

		$paint = Paint::create();
		$paint->output('Tests/carlos.jpeg');
	}

	public function testOutputNotWritable()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->output('Tests/undefined/carlos.jpeg');
	}

	public function testSupportedFormat()
	{
		$this->assertEquals(true, Paint::isSupportedFormats(IMG_GIF));
		$this->assertEquals(true, Paint::isSupportedFormats(IMG_JPG));
		$this->assertEquals(true, Paint::isSupportedFormats(IMG_PNG));
		$this->assertEquals(true, Paint::isSupportedFormats(IMG_WBMP));
	}

	public function testUnsupportedFormat()
	{
		$paint = Paint::create();
		$this->assertEquals(false, Paint::isSupportedFormats('unknow'));
	}

	public function testValidOutputFormat()
	{
		$paint = Paint::create();
		$paint->outputFormat(IMG_GIF);
		$paint->outputFormat(IMG_JPEG);
		$paint->outputFormat(IMG_PNG);
		$paint->outputFormat(IMG_WBMP);
	}

	public function testInvalidOutputFormat()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->outputFormat('unknow');
	}

	public function testGenerate()
	{
		$file = 'Tests/output.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testGeneratePNG()
	{
		$file = 'Tests/output.png';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->outputFormat(IMG_PNG);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateGIF()
	{
		$file = 'Tests/output.gif';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->outputFormat(IMG_GIF);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testGenerateWBMP()
	{
		$file = 'Tests/output.wbmp';
		
		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->outputFormat(IMG_WBMP);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testColorFill()
	{
		$file = 'Tests/output-red.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->colorFill(255, 0, 0);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));

		$img = imagecreatefromjpeg($file);
		$rgb = imagecolorsforindex($img, imagecolorat($img, 1, 1));
		
		$this->assertEquals(254, $rgb['red']);
		$this->assertEquals(0, $rgb['green']);
		$this->assertEquals(0, $rgb['blue']);
	}

	// public function testWbmpForeground()
	// {
	// 	$file = 'Tests/foreground.wbmp';

	// 	if (file_exists($file)) {
	// 		unlink($file);
	// 	}

	// 	$paint = Paint::create();
	// 	$paint->setOutputSize(100, 100);
	// 	$paint->setForeground(255, 0, 0);
	// 	$paint->outputFormat(IMG_WBMP);
	// 	$paint->output($file);
	// 	$paint->generate();

	// 	$this->assertEquals(true, file_exists($file));

	// 	$img = imagecreatefromwbmp($file);
	// 	$rgb = imagecolorsforindex($img, imagecolorat($img, 1, 1));
		
	// 	$this->assertEquals(255, $rgb['red']);
	// 	$this->assertEquals(0, $rgb['green']);
	// 	$this->assertEquals(0, $rgb['blue']);
	// }

	public function testResizeSmaller()
	{
		$file = 'Tests/resize-smaller.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->setOutputSize(300, 300);
		$paint->outputFormat(IMG_JPEG);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testResizeBigger()
	{
		$file = 'Tests/resize-bigger.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->setOutputSize(1024, 768, Paint::RESIZE_FIT);
		$paint->outputFormat(IMG_JPEG);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

	public function testCrop()
	{
		$file = 'Tests/resize-crop.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->setOutputSize(300, 300, Paint::RESIZE_CROP);
		$paint->outputFormat(IMG_JPEG);
		$paint->output($file);
		$paint->generate();

		$this->assertEquals(true, file_exists($file));
	}

}