<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies\Profile\TestNamespace;

use Flinnt\Core\View\Widgets\AbstractWidget;

class TestFeed extends AbstractWidget
{

	protected $slides = 6;

	public function run()
	{
		return 'Feed was executed with $slides = ' . $this->slides;
	}
}
