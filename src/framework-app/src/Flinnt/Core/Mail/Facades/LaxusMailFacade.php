<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 4/11/16
 * Time: 3:39 PM
 */

namespace Flinnt\Core\Mail\Facades;


use Flinnt\Core\Mail\Providers\LaxusMailServiceProvider;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Facade;

/**
 * Class LaxusMailFacade
 *
 * @package Flinnt\Core\Mail\Facades
 * @see     LaxusMailServiceProvider
 * @see     Mailer
 */
class LaxusMailFacade extends Facade
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
		return 'laxusmailer';
	}

}