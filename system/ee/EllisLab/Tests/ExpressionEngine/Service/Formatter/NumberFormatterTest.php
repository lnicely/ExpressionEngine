<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\Tests\ExpressionEngine\Service\Formatter;

use Mockery as m;
use EllisLab\ExpressionEngine\Service\Formatter\Formats\Number;

class NumberFormatterTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->lang = m::mock('EE_Lang');
		$this->sess = m::mock('EE_Session');
	}

	/**
	 * @dataProvider byteProvider
	 */
	public function testByte($content, $abbr, $include_markup, $expected)
	{
		$this->lang->shouldReceive('load')->once();
		$number = (string) $this->format($content)->bytes($abbr, $include_markup);
		$this->assertEquals($expected, $number);
	}

	public function byteProvider()
	{
		// sets the byte() parameters and expected lang key suffix
		// array($abbr, $include_markup, 'suffix')
		$permutations = array(
			array(FALSE, FALSE, ''),
			array(TRUE, FALSE, '_abbr'),
			array(TRUE, TRUE, '_abbr_html'),
			array(FALSE, TRUE, '')
		);

		$data = array();
		foreach ($permutations as $p)
		{
			// non-abbreviated lang keys should be proceeded with a space
			$space = ($p[0]) ? '' : ' ';

			$data = array_merge($data, array(
				array(1073741824, $p[0], $p[1], "1.00{$space}formatter_gigabytes{$p[2]}"),
				array(10732049531, $p[0], $p[1], "10.00{$space}formatter_gigabytes{$p[2]}"),
				array(10732049530, $p[0], $p[1], "9.99{$space}formatter_gigabytes{$p[2]}"),

				array(1048576, $p[0], $p[1], "1.0{$space}formatter_megabytes{$p[2]}"),
				array(10433332, $p[0], $p[1], "10.0{$space}formatter_megabytes{$p[2]}"),
				array(10433331, $p[0], $p[1], "9.9{$space}formatter_megabytes{$p[2]}"),

				array(1024, $p[0], $p[1], "1{$space}formatter_kilobytes{$p[2]}"),
				array(10752, $p[0], $p[1], "11{$space}formatter_kilobytes{$p[2]}"),
				array(10751, $p[0], $p[1], "10{$space}formatter_kilobytes{$p[2]}"),

				array(999, $p[0], $p[1], "999{$space}formatter_bytes{$p[2]}"),
			));
		}

		return $data;
	}

	/**
	 * @dataProvider currencyProvider
	 */
	public function testCurrency($content, $currency, $locale, $expected, $opts)
	{
		$this->lang->shouldReceive('load')->once();

		$params = [
			'currency' => $currency,
			'locale' => $locale,
		];

		$number = (string) $this->format($content, $opts)->currency($params);
		$this->assertEquals($expected, $number);
	}

	public function currencyProvider()
	{
		return [
			// with intl extension
			[112358.13, NULL, NULL, '$112,358.13', 0b00000001],
			[112358.13, 'EUR', 'de_DE', '112.358,13 €', 0b00000001],
			[112358.13, 'GBP', 'en_UK', '£112,358.13', 0b00000001],
			[112358.13, 'AUD', 'en_US.UTF-8', 'A$112,358.13', 0b00000001],
			[112358.13, 'AUD', 'de_DE', '112.358,13 AU$', 0b00000001],
			[112358.13, 'RUR', 'ru', '112 358,13 р.', 0b00000001],
			[112358.13, 'UAH', 'uk', '112 358,13 ₴', 0b00000001],
			[112358.13, 'UAH', 'en', (version_compare(INTL_ICU_VERSION, '4.8', '>') ? 'UAH112,358.13' : '₴1,234,567.89'), 0b00000001],
			['fake', NULL, NULL, '$0.00', 0b00000001],

			// no intl extension
			[112358.13, NULL, NULL, '$112,358.13', 0],
			[112358.13, 'EUR', 'de_DE', 'Eu112.358,13', 0],
			[112358.13, 'GBP', 'en_UK', '112358.13', 0],
			[112358.13, 'AUD', 'en_US.UTF-8', '$112,358.13', 0],
			[112358.13, 'AUD', 'de_DE', 'Eu112.358,13', 0],
			[112358.13, 'RUR', 'ru', '112358.13', 0],
			[112358.13, 'UAH', 'uk', '112358.13', 0],
			[112358.13, 'UAH', 'en', '112358.13', 0],
			['fake', NULL, NULL, '$0.00', 0],
		];
	}

	/**
	 * @dataProvider durationProvider
	 */
	public function testDuration($content, $expected, $opts)
	{
		$this->lang->shouldReceive('load')->once();
		$val = (string) $this->format($content, $opts)->duration();
		$this->assertEquals($expected, $val);
	}

	public function durationProvider()
	{
		return [
			// with intl extension
			[112358, '31:12:38', 0b00000001],
			[-112358, '-32:-13:-38', 0b00000001],
			[1123, '18:43', 0b00000001],
			[11, '11 sec.', 0b00000001],
			['fake', '0 sec.', 0b00000001],

			// no intl extension
			// don't have a good way to test the output of a sprintf()'d language variable
			[112358, '31:12:38', 0],
			[-112358, 'formatter_duration_seconds_only', 0],
			[1123, '18:43', 0],
			[11, 'formatter_duration_seconds_only', 0],
			['fake', 'formatter_duration_seconds_only', 0],
		];
	}
	/**
	 * @dataProvider ordinalProvider
	 */
	public function testOrdinal($content, $locale, $expected, $opts)
	{
		$this->lang->shouldReceive('load')->once();

		$number = (string) $this->format($content, $opts)->ordinal(['locale' => $locale]);
		$this->assertEquals($expected, $number);
	}

	public function ordinalProvider()
	{

		return [
			// with intl extension
			[11235813, NULL, '11,235,813th', 0b00000001],
			[11235813, 'de', '11.235.813.', 0b00000001],
			[11235813, 'fr', '11 235 813e', 0b00000001],
			['fake', NULL, '0th', 0b00000001],

			// no intl extension
			[11235813, NULL, '11,235,813th', 0],
			[11235813, 'de', '11,235,813th', 0],
			[11235813, 'fr', '11,235,813th', 0],
			['fake', NULL, '0th', 0],
		];
	}

	public function tearDown()
	{
		$this->factory = NULL;
	}

	public function format($content, $options = 0b00000001)
	{
		return new Number($content, $this->lang, $this->sess, [], $options);
	}
}

// EOF
