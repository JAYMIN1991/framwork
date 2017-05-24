<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/11/16
 * Time: 7:03 PM
 */

namespace Flinnt\BackOffice\Support\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class BackOfficeHelper
 *
 * @see     BackOfficeHelper
 * @package Flinnt\BackOffice\Support\Facades
 */
class Helpers extends Facade
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
		return "backofficehelper";
	}
}