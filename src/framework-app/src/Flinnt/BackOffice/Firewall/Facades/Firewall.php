<?php
namespace Flinnt\BackOffice\Firewall\Facades;

Use Illuminate\Support\Facades\Facade;


/**
 * Class Firewall
 *
 * @package Flinnt\BackOffice\Firewall\Facades
 */
class Firewall extends Facade
{

	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{

		return 'Firewall';

	}


}