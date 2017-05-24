<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 6:12 PM
 */

namespace Flinnt\BackOffice\View;


use Flinnt\Core\Contracts\Controller\IController;
use Flinnt\Core\View\AbstractViewSidebar;

/**
 * Class BackOfficeSidebar
 *
 * @package Flinnt\BackOffice\View
 */
class BackOfficeSidebar extends AbstractViewSidebar
{

	/**
	 * AbstractViewSidebar constructor.
	 *
	 * @param IController $controller object of any class implemented from IController
	 * @param array       $config     Associative array of configuration parameters to generate the sidebar
	 */
	public function __construct( IController $controller, array $config = [] )
	{
		$this->parentController = $controller;
		$this->config = $config;
		$this->boot();
	}

	/**
	 * Generate the content of sidebar
	 *
	 * @return Void
	 */
	protected function boot()
	{
		// TODO: Implement boot() method.
	}
}