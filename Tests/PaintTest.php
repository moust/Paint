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
		if (file_exists('Tests/output.jpeg')) {
			unlink('Tests/output.jpeg');
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->output('Tests/output.jpeg');
		$paint->generate();

		$this->assertEquals(true, file_exists('Tests/output.jpeg'));
	}

	// public function testWbmpForeground()
	// {
	// 	$file = 'Tests/output.wbmp';

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
}