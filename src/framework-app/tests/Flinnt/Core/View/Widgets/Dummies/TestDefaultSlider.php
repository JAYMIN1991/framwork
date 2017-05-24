<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

/**
 * Class TestDefaultSlider
 *
 * @package Flinnt\Core\View\Widgets\Test\Dummies
 */
class TestDefaultSlider extends AbstractWidget
{

	protected $slides = 6;

	/**
	 * @return string
	 */
	public function run()
	{
		return 'Default test slider was executed with $slides = ' . $this->slides;
	}
}
