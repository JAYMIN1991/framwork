<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/11/16
 * Time: 12:01 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Permission\Extensions;


/**
 * Class PermissionExtension
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Permission\Extensions
 */
class PermissionExtension extends \Twig_Extension
{

	/**
	 * {@inheritdoc}
	 */
	public function getTokenParsers()
	{
		return array(app('Flinnt\Core\View\Engines\Twig\Tags\Permission\TokenParser\PermissionTokenParser'),);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
	 */
	/*public function getName()
	{
		return 'permissions';
	}*/
}