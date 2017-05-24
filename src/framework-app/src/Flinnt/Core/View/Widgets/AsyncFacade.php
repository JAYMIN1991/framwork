<?php

namespace Flinnt\Core\View\Widgets;

/**
 * Class AsyncFacade
 *
 * @package Flinnt\Core\View\Widgets
 */
class AsyncFacade extends \Illuminate\Support\Facades\Facade
{

	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'flinnt.core.view.async-widget';
	}
}
