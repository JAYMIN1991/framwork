<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 18/11/16
 * Time: 6:33 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Cache\Extensions;


/**
 * Class CacheExtension
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Cache\Extensions
 */
class CacheExtension extends \Twig_Extension
{

	/**
	 * {@inheritdoc}
	 */
	public function getTokenParsers()
	{
//		$parser =
		return array(app('Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser\CacheTokenParser'),);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
	 */
	public function getName()
	{
		return 'cache';
	}
}