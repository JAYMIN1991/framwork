<?php

namespace Flinnt\Core\View\Widgets;

/**
 * Class Facade
 *
 * @package Flinnt\Core\View\Widgets
 */
class Facade extends \Illuminate\Support\Facades\Facade
{

	/**
	 * Get the widget group object.
	 *
	 * @param $name
	 *
	 * @return WidgetGroup
	 */
	public static function group( $name )
	{
		return app('flinnt.core.view.widget-group-collection')->group($name);
	}

	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'flinnt.core.view.widget';
	}
}
