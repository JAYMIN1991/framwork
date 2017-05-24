<?php

namespace Flinnt\Core\View\Widgets\Misc;

use Exception;

/**
 * Class InvalidWidgetClassException
 *
 * @package Flinnt\Core\View\Widgets\Misc
 */
class InvalidWidgetClassException extends Exception
{

	/**
	 * Exception message.
	 *
	 * @var string
	 */
	protected $message = 'Widget class must extend Flinnt\Core\View\Widgets\AbstractWidget class';
}
