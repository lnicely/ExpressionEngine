<?php
namespace EllisLab\Tests\ExpressionEngine\Service;

use EllisLab\ExpressionEngine\Service\Filter\Custom;
use Mockery as m;

class CustomTest extends \PHPUnit_Framework_TestCase {

	protected $options = array(
		'whatthefoxsay' => 'Ring-ding-ding-ding-dingeringeding!',
		'42' => 'The Answer',
		'9.1' => 'Floating'
	);

	public function tearDown()
	{
		unset($_POST['filter_by_custom']);
		unset($_GET['filter_by_custom']);
	}

	public function testDefault()
	{
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertNull($filter->value(), 'The value is NULL by default.');
		$this->assertTrue($filter->isValid(), 'The default is valid');

		$vf = m::mock('EllisLab\ExpressionEngine\Service\View\ViewFactory');
		$url = m::mock('EllisLab\ExpressionEngine\Library\CP\URL');

		$vf->shouldReceive('make->render');
		$url->shouldReceive('setQueryStringVariable', 'compile');
		$filter->render($vf, $url);
	}

	public function testPOST()
	{
		$_POST['filter_by_custom'] = 'whatthefoxsay';
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals('whatthefoxsay', $filter->value(), 'The value reflects the POSTed value');
		$this->assertTrue($filter->isValid(), 'POSTing "whatthefoxsay" is valid');
	}

	public function testGET()
	{
		$_GET['filter_by_custom'] = 'whatthefoxsay';
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals('whatthefoxsay', $filter->value(), 'The value reflects the GETed value');
		$this->assertTrue($filter->isValid(), 'GETing "whatthefoxsay" is valid');
	}

	public function testPOSTOverGET()
	{
		$_POST['filter_by_custom'] = 'whatthefoxsay';
		$_GET['filter_by_custom'] = 42;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals('whatthefoxsay', $filter->value(), 'Use POST over GET');
	}

	// Use GET when POST is present but "empty"
	public function testGETWhenPOSTIsEmpty()
	{
		$_POST['filter_by_custom'] = '';
		$_GET['filter_by_custom'] = 42;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals(42, $filter->value(), 'Use GET when POST is an empty string');

		$_POST['filter_by_custom'] = NULL;
		$_GET['filter_by_custom'] = 42;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals(42, $filter->value(), 'Use GET when POST is NULL');

		$_POST['filter_by_custom'] = 0;
		$_GET['filter_by_custom'] = 42;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals(42, $filter->value(), 'Use GET when POST is 0');

		$_POST['filter_by_custom'] = "0";
		$_GET['filter_by_custom'] = 42;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals(42, $filter->value(), 'Use GET when POST is "0"');
	}

	/**
	 * @dataProvider validityDataProvider
	 */
	public function testValdity($submitted, $valid)
	{
		$_POST['filter_by_custom'] = $submitted;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals($submitted, $filter->value());
		if ($valid)
		{
			$this->assertTrue($filter->isValid(), '"' . $submitted . '" is valid');
		}
		else
		{
			$this->assertFalse($filter->isValid(), '"' . $submitted . '" is invalid');
		}

		unset($_POST['filter_by_custom']);
		$_GET['filter_by_custom'] = $submitted;
		$filter = new Custom('filter_by_custom', 'custom', $this->options);
		$this->assertEquals($submitted, $filter->value());
		if ($valid)
		{
			$this->assertTrue($filter->isValid(), '"' . $submitted . '" is valid');
		}
		else
		{
			$this->assertFalse($filter->isValid(), '"' . $submitted . '" is invalid');
		}
	}

	public function validityDataProvider()
	{
		return array(
			array('whatthefoxsay', TRUE),
			array(42, TRUE),
			array('42', TRUE),
			array('9.1', TRUE),

			// Some missing keys
			array('WhatTheFoxSay', FALSE),
			array('1', FALSE),
			array(-1, FALSE)
		);
	}

}