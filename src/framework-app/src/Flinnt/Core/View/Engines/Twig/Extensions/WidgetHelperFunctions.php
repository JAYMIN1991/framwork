<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 7/11/16
 * Time: 2:28 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use Illuminate\Foundation\Application;


/**
 * This class is used for registering widget related function with twig. like, widget, asyncWidget, widgetGroup, widgetGroupDisplay
 *
 * Class WidgetHelperFunctions
 * @package Flinnt\Core\View\Engines\Twig\Extensions
 */
class WidgetHelperFunctions extends Twig_Extension
{

	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Create a new
	 *
	 * @param \Illuminate\Foundation\Application
	 */
	public function __construct( Application $app )
	{
		$this->app = $app;


	}

	/**
	 * Returns array of functions registered with Twig
	 *
	 * @return array
	 */
	public function getFunctions()
	{
		return [new Twig_SimpleFunction('widget', [$this->app['flinnt.core.view.widget'], 'run'], ['is_safe' => ['html']]), new Twig_SimpleFunction('asyncWidget', [$this->app['flinnt.core.view.async-widget'], 'run'], ['is_safe' => ['html']]), new Twig_SimpleFunction('widgetGroup', [$this->app['flinnt.core.view.widget-group-collection'], 'group'], ['is_safe' => ['html']]), new Twig_SimpleFunction('widgetGroupDisplay', function () {
			$arguments = func_get_args();
			$widgetGroup = call_user_func_array([$this->app['flinnt.core.view.widget-group-collection'], 'group'], $arguments);

			return $widgetGroup->display();
		}),


		];
	}

	/**
	 * Returns Name of Twig Extension
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'TwigBridge_Extension_LaravelWidget';
	}

}