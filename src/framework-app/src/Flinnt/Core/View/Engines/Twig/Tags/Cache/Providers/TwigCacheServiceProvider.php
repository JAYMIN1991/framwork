<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 19/11/16
 * Time: 2:58 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Cache\Providers;


use Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser\CacheTokenParser;
use Illuminate\Support\ServiceProvider;

/**
 * Class TwigCacheServiceProvider
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Cache\Providers
 */
class TwigCacheServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the cache tag token parser with laravel container
	 */
	public function register()
	{
		$this->app->singleton(CacheTokenParser::class);
	}
}