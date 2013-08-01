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
}