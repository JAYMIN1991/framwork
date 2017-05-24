<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 13/10/16
 * Time: 6:28 PM
 */

namespace Flinnt\BackOffice\Firewall;

use M6Web\Component\Firewall\Firewall as M6WebFirewall;
use Config;


/**
 * Class Firewall
 *
 * @package Flinnt\BackOffice\Firewall
 */
class Firewall
{

	protected $firewall;

	/**
	 * Firewall constructor.
	 *
	 * @internal param $firewall
	 */
	public function __construct()
	{

		$this->firewall = new M6WebFirewall();

		if ( Config::has('firewall.setDefaultState') ) {
			$this->firewall->setDefaultState(Config::get('firewall.setDefaultState'));
		}

		if ( Config::has('firewall.whitelist') && count(Config::get('firewall.whitelist')) > 0 ) {
			$this->firewall->addList(Config::get('firewall.whitelist'), 'global_local', true);
		}

		if ( Config::has('firewall.blacklist') && count(Config::get('firewall.blacklist')) > 0 ) {
			$this->firewall->addList(Config::get('firewall.blacklist'), 'global_localBad', false);
		}

		if ( Config::has('firewall.setIpAddress') && Config::get('firewall.setIpAddress') != false ) {
			$this->firewall->setIpAddress(Config::get('firewall.setIpAddress'));
		}

	}


	// route all other method calls directly to M6WebFirewall
	/**
	 * @param $method
	 * @param $args
	 *
	 * @return bool|mixed
	 */
	public function __call( $method, $args )
	{
		if ( ! method_exists($this->firewall, $method) ) {
			//throw new Exception("Undefined method $method attempt in the Url class here.");
			return false;
		}

		return call_user_func_array(array($this->firewall, $method), $args);
	}
}