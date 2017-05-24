<?php

namespace Flinnt\Core\View\Engines\Twig\Extensions;

use Illuminate\Contracts\Routing\UrlGenerator;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class HelperFunctions
 *
 * @package Flinnt\Core\View\Engines\Twig\Extensions
 */
class HelperFunctions extends Twig_Extension
{

	protected $url;

	/**
	 * FlinntTwigBridgeExtension constructor.
	 *
	 * @param \Illuminate\Contracts\Routing\UrlGenerator $url
	 */
	public function __construct( UrlGenerator $url )
	{
		$this->url = $url;
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return [new Twig_SimpleFunction('route_relative', [$this, 'routeRelative'], ['is_safe' => ['html']]), new Twig_SimpleFunction('asset_path', [$this, 'assetPath'], ['is_safe' => ['html']]),];
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'TwigBridge_Extension_Custom_RouteExtension';
	}

	/**
	 * Get the relative path for routes
	 *
	 * @param string $name name of the route
	 *
	 * @param array  $parameters Array of parameters
	 *
	 * @return string returns relative path for route
	 */
	public function routeRelative( $name, $parameters = [] )
	{
		return route($name, $parameters, false);
	}

	/**
	 * Get the relative path for assets
	 *
	 * @param string $path relative path of assets(css, js, images etc.) from public folder
	 *
	 * @return string relative path for assets
	 */
	public function assetPath( $path )
	{
		return '/' . $path;
	}

}