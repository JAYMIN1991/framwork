<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/11/16
 * Time: 12:02 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Permission\Providers;


use Flinnt\Core\View\Engines\Twig\Tags\Permission\TokenParser\PermissionTokenParser;
use Illuminate\Support\ServiceProvider;

/**
 * Class PermissionTagServiceProvider
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Permission\Providers
 */
class PermissionTagServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the permission tag token parser with laravel application.
	 */
	public function register()
	{
		$this->app->singleton(PermissionTokenParser::class);
	}
}