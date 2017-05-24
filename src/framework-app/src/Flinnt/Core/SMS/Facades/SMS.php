<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/11/16
 * Time: 7:03 PM
 */

namespace Flinnt\Core\SMS\Facades;


use Flinnt\Core\SMS\SMSHandler;
use Illuminate\Support\Facades\Facade;

/**
 * Class SMS
 *
 * @see     SMSHandler
 * @package Flinnt\Core\SMS\Facade
 */
class SMS extends Facade
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
		return "sms";
	}
}