<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 29/11/16
 * Time: 12:23 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Role\Extensions;


/**
 * Class RoleExtension
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Role\Extensions
 */
class RoleExtension extends \Twig_Extension
{

	/**
	 * {@inheritdoc}
	 */
	public function getTokenParsers()
	{
		return array(app('Flinnt\Core\View\Engines\Twig\Tags\Role\TokenParser\RoleTokenParser'),);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
	 */
	public function getName()
	{
		return 'role';
	}
}