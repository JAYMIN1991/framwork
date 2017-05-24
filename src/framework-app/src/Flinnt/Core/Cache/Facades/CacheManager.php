<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 22/11/16
 * Time: 3:09 PM
 */

namespace Flinnt\Core\Cache\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class CacheManager
 *
 * @package Flinnt\Core\Cache\Facades
 */
class CacheManager extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	protected static function getFacadeAccessor()
	{
		return "cachemanager";
	}

}