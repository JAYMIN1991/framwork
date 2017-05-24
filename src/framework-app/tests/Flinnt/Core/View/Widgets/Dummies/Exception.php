<?php

namespace Flinnt\Core\View\Widgets\Test\Dummies;

use Flinnt\Core\View\Widgets\AbstractWidget;

/**
 * Class Exception
 *
 * @package Flinnt\Core\View\Widgets\Test\Dummies
 */
class Exception extends AbstractWidget
{

	/**
	 * @return string
	 */
	public function run()
	{
		return 'Exception widget was executed instead of predefined php class';
	}
}
