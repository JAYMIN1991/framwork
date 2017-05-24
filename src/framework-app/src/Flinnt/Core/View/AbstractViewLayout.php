<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 5:59 PM
 */

namespace Flinnt\Core\View;


use Flinnt\Core\Contracts\Controller\IController;
use Flinnt\Core\Contracts\View\IViewLayout;

/**
 * Class AbstractViewLayout
 *
 * @package Flinnt\Core\View
 */
abstract class AbstractViewLayout implements IViewLayout
{

	/**
	 * @var string
	 */
	protected $content = "";

	/**
	 * @var IController
	 */
	protected $parentController;

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * AbstractViewLayout constructor.
	 *
	 * @param IController $controller object of any class implemented from IController
	 * @param array       $config     Associative array of configuration parameters to generate the header or footer layout
	 */
	abstract public function __construct( IController $controller, array $config = [] );

	/**
	 * Return the content of header or footer layout
	 *
	 * @return String
	 *
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Generate the content of header or footer layout
	 *
	 * @return Void
	 */
	abstract protected function boot();


}