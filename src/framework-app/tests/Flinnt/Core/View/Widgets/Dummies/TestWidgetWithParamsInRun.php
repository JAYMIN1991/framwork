<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

class TestWidgetWithParamsInRun extends AbstractWidget
{

	public function run( $flag )
	{
		return 'TestWidgetWithParamsInRun was executed with $flag = ' . $flag;
	}

	public function placeholder()
	{
		return 'Placeholder here!';
	}
}
