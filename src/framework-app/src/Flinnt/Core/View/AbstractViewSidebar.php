<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 5:13 PM
 */

namespace Flinnt\Core\View;


use Flinnt\Core\Contracts\Controller\IController;
use Flinnt\Core\Contracts\View\IViewSidebar;

/**
 * Class AbstractViewSidebar
 * @package Flinnt\Core\View
 */
abstract class AbstractViewSidebar implements IViewSidebar
{

	/**
	 * @var string
	 */
	protected $content = "";

	/**
	 * @var bool
	 */
	protected $hasContent = false;

	/**
	 * @var IController
	 */
	protected $parentController;

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * AbstractViewSidebar constructor.
	 *
	 * @param IController $controller object of any class implemented from IController
	 * @param array       $config     Associative array of configuration parameters to generate the sidebar
	 */
	abstract public function __construct( IController $controller, array $config = [] );

	/**
	 * Return the content of sidebar
	 *
	 * @return String
	 *
	 */
	public function getContent()
	{
		if ( ! $this->hasContent ) {
			return "";
		}

		return $this->content;
	}

	/**
	 * Check if sidebar has content or not
	 *
	 * @return bool
	 *
	 */
	public function hasContent()
	{
		return $this->hasContent();
	}

	/**
	 * Generate the content of sidebar
	 *
	 * @return Void
	 */
	abstract protected function boot();

}