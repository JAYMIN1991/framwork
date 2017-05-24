<?php

namespace Flinnt\Core\DBLog\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class DBLog
 *
 * @package Flinnt\Core\DBLog\Facade
 */
class DBLog extends Facade
{

	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return "dblog";
	}

}