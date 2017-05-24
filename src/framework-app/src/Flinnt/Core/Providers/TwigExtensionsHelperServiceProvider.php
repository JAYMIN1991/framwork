<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 19/12/16
 * Time: 6:08 PM
 */

namespace Flinnt\Core\Providers;


use Illuminate\Support\ServiceProvider;

/**
 * Register helper functions to laravel application
 *
 * Class TwigExtensionsHelperServiceProvider
 *
 * @package Flinnt\BackOffice\Providers
 */
class TwigExtensionsHelperServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register custom and widget helper functions to laravel application
	 */
	public function register()
	{
		require_once("../View/Engines/Twig/Extensions/HelperFunctions.php");
		require_once("../View/Engines/Twig/Extensions/WidgetHelperFunctions.php");
	}
}