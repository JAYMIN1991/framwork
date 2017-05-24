<?php

namespace Flinnt\Core\View\Widgets\Test\Support;

use Flinnt\Core\View\Widgets\Contracts\ApplicationWrapperContract;
use Flinnt\Core\View\Widgets\Factories\AsyncWidgetFactory;
use Flinnt\Core\View\Widgets\Factories\WidgetFactory;
use Closure;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class TestApplicationWrapper
 *
 * @package Flinnt\Core\View\Widgets\Test\Support
 */
class TestApplicationWrapper implements ApplicationWrapperContract
{

	/**
	 * Configuration array double.
	 *
	 * @var array
	 */
	public $config = ['widgets.default_namespace' => 'Flinnt\Core\View\Widgets\Test\Dummies', 'widgets.use_jquery_for_ajax_calls' => true,];

	/**
	 * Wrapper around Cache::remember().
	 *
	 * @param         $key
	 * @param         $minutes
	 * @param Closure $callback
	 *
	 * @return mixed
	 */
	public function cache( $key, $minutes, Closure $callback )
	{
		return 'Cached output. Key: ' . $key . ', minutes: ' . $minutes;
	}

	/**
	 * Wrapper around app()->call().
	 *
	 * @param       $method
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function call( $method, $params = [] )
	{
		return call_user_func_array($method, $params);
	}

	/**
	 * Get the specified configuration value.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 * @throws \Doctrine\Instantiator\Exception\InvalidArgumentException
	 */
	public function config( $key, $default = null )
	{
		if ( isset($this->config[$key]) ) {
			return $this->config[$key];
		}

		throw new InvalidArgumentException("Key {$key} is not defined for testing");
	}

	/**
	 * Wrapper around app()->getNamespace().
	 *
	 * @return string
	 */
	public function getNamespace()
	{
		return 'App\\';
	}

	/**
	 * Wrapper around app()->make().
	 *
	 * @param string $abstract
	 * @param array  $parameters
	 *
	 * @return mixed
	 * @throws \Doctrine\Instantiator\Exception\InvalidArgumentException
	 */
	public function make( $abstract, array $parameters = [] )
	{
		if ( $abstract == 'flinnt.core.view.widget' ) {
			return new WidgetFactory($this);
		}

		if ( $abstract == 'flinnt.core.view.async-widget' ) {
			return new AsyncWidgetFactory($this);
		}

		if ( $abstract == 'encrypter' ) {
			return new TestEncrypter();
		}

		throw new InvalidArgumentException("Binding {$abstract} cannot be resolved while testing");
	}
}
