<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 29/11/16
 * Time: 6:01 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Role\Providers;


use Flinnt\Core\View\Engines\Twig\Tags\Role\TokenParser\RoleTokenParser;
use Illuminate\Support\ServiceProvider;

/**
 * Class RoleTagServiceProvider
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Role\Providers
 */
class RoleTagServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->app->singleton(RoleTokenParser::class);
	}

}