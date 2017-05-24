<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

class TestRepeatableFeed extends AbstractWidget
{

	/**
	 * The number of seconds before reload from server.
	 *
	 * @var float|int
	 */
	public $reloadTimeout = 10;
	protected $slides = 6;

	public function run()
	{
		return 'Feed was executed with $slides = ' . $this->slides;
	}
}
