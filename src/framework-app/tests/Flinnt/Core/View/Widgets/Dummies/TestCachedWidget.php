<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

/**
 * Class TestCachedWidget
 *
 * @package Flinnt\Core\View\Widgets\Test\Dummies
 */
class TestCachedWidget extends AbstractWidget
{

	public $cacheTime = 60;

	protected $slides = 6;

	/**
	 * @return string
	 */
	public function run()
	{
		return 'Feed was executed with $slides = ' . $this->slides;
	}
}
