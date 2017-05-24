<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

/**
 * Class Slider
 *
 * @package Flinnt\Core\View\Widgets\Test\Dummies
 */
class Slider extends AbstractWidget
{

	protected $config = ['slides' => 6, 'foo' => 'bar',];

	/**
	 * @return string
	 */
	public function run()
	{
		return 'Slider was executed with $slides = ' . $this->config['slides'] . ' foo: ' . $this->config['foo'];
	}

	/**
	 * @return string
	 */
	public function placeholder()
	{
		return 'Placeholder here!';
	}
}
