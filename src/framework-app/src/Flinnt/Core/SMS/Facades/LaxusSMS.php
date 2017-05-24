<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/11/16
 * Time: 5:04 PM
 */

namespace Flinnt\Core\SMS\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class LaxusSMS
 *
 * @package Flinnt\Core\SMS\Facades
 */
class LaxusSMS extends Facade
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
		return "laxussms";
	}

}