<?php

namespace Flinnt\Core\View\Widgets\Test\Support;

use Flinnt\Core\View\Widgets\WidgetId;
use PHPUnit_Framework_TestCase;

/**
 * Class TestCase
 *
 * @package Flinnt\Core\View\Widgets\Test\Support
 */
class TestCase extends PHPUnit_Framework_TestCase
{

	public function tearDown()
	{
		WidgetId::reset();
	}

	/**
	 * @param       $widgetName
	 * @param array $widgetParams
	 * @param int   $id
	 *
	 * @return string
	 */
	public function ajaxUrl( $widgetName, $widgetParams = [], $id = 1 )
	{
		return '/arrilot/load-widget?' . http_build_query(['id' => $id, 'name' => $widgetName, 'params' => json_encode($widgetParams),]);
	}
}
