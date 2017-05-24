<?php

namespace Flinnt\Core\View\Widgets\Controllers;

use Flinnt\Core\View\Widgets\Factories\AbstractWidgetFactory;
use Flinnt\Core\View\Widgets\WidgetId;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class WidgetController
 *
 * @package Flinnt\Core\View\Widgets\Controllers
 */
class WidgetController extends BaseController
{

	/**
	 * The action to show widget output via ajax.
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function showWidget( Request $request )
	{
		$this->prepareGlobals($request);

		$factory = app()->make('flinnt.core.view.widget');
		$widgetName = $request->input('name', '');
		$widgetParams = $factory->decryptWidgetParams($request->input('params', ''));


		return call_user_func_array([$factory, $widgetName], $widgetParams);
	}

	/**
	 * Set some specials variables to modify the workflow of the widget factory.
	 *
	 * @param Request $request
	 */
	protected function prepareGlobals( Request $request )
	{
		WidgetId::set($request->input('id', 1) - 1);
		AbstractWidgetFactory::$skipWidgetContainer = true;
	}
}
