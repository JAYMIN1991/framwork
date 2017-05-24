<?php

namespace Flinnt\Core\View\Widgets;

use Flinnt\Core\View\Widgets\Contracts\ApplicationWrapperContract;
use Flinnt\Core\View\Widgets\Misc\ViewExpressionTrait;

/**
 * Class WidgetGroup
 *
 * @package Flinnt\Core\View\Widgets
 */
class WidgetGroup
{

	use ViewExpressionTrait;

	/**
	 * The widget group name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The application wrapper.
	 *
	 * @var ApplicationWrapperContract
	 */
	protected $app;

	/**
	 * The array of widgets to display in this group.
	 *
	 * @var array
	 */
	protected $widgets = [];

	/**
	 * The position of a widget in this group.
	 *
	 * @var int
	 */
	protected $position = 100;

	/**
	 * The separator to display between widgets in the group.
	 *
	 * @var string
	 */
	protected $separator = '';

	/**
	 * The number of widgets in the group.
	 *
	 * @var int
	 */
	protected $count = 0;

	/**
	 * @param                            $name
	 * @param ApplicationWrapperContract $app
	 */
	public function __construct( $name, ApplicationWrapperContract $app )
	{
		$this->name = $name;

		$this->app = $app;
	}

	/**
	 * Set widget position.
	 *
	 * @param int $position
	 *
	 * @return $this
	 */
	public function position( $position )
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * Add a widget to the group.
	 */
	public function addWidget()
	{
		$this->addWidgetWithType('sync', func_get_args());
	}

	/**
	 * Add a widget with a given type to the array. This function is overrrided to support widget position override
	 *
	 * @param string $type
	 * @param array  $arguments
	 */
	protected function addWidgetWithType( $type, array $arguments = [] )
	{
		if ( ! isset($this->widgets[$this->position]) ) {
			$this->widgets[$this->position] = [];
		}


		$this->widgets[$this->position][] = ['arguments' => $arguments, 'type' => $type,];

		$this->count++;

		$this->resetPosition();

		/*$this->widgets[$this->position] = [];

		$this->widgets[$this->position][] = [
			'arguments' => $arguments,
			'type'      => $type,
		];

		if (!isset($this->widgets[$this->position])) {
			$this->count++;
		}
		else
		{
			//var_dump('you are trying to override for the position'.$this->position);
		}

		$this->resetPosition();*/

	}

	/**
	 * Reset the position property back to the default.
	 * So it does not affect the next widget.
	 */
	protected function resetPosition()
	{
		$this->position = 100;
	}

	/**
	 * Add an async widget to the group.
	 */
	public function addAsyncWidget()
	{
		$this->addWidgetWithType('async', func_get_args());
	}

	/**
	 * Getter for position.
	 *
	 * @return array|int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set a separator to display between widgets in the group.
	 *
	 * @param string $separator
	 *
	 * @return $this
	 */
	public function setSeparator( $separator )
	{
		$this->separator = $separator;

		return $this;
	}

	/**
	 * Check if there are any widgets in the group.
	 *
	 * @return bool
	 */
	public function any()
	{
		return ! $this->isEmpty();
	}

	/**
	 * Check if there are no widgets in the group.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->widgets);
	}

	/**
	 * Count the number of widgets in this group.
	 *
	 * @return int
	 */
	public function count()
	{
		$count = 0;
		foreach ( $this->widgets as $position => $widgets ) {
			$count += count($widgets);
		}

		return $count;
	}

	/**
	 * Replace a widget to the group.
	 */
	public function replaceWidget()
	{
		$this->replaceWidgetWithType('sync', func_get_args());
	}

	/**
	 * Replace a widget with a given type to the array.
	 *
	 * @param string $type
	 * @param array  $arguments
	 *
	 * @throws \Exception
	 */
	protected function replaceWidgetWithType( $type, array $arguments = [] )
	{
		if ( isset($this->widgets[$this->position]) ) {

			$this->count -= count($this->widgets[$this->position]);
			unset($this->widgets[$this->position]);

			$this->addWidgetWithType($type, $arguments);
		}
		else {
			throw new \Exception("There is no widget to replace on position $this->position!");
		}


	}

	/**
	 * Replace an async widget to the group.
	 */
	public function replaceAsyncWidget()
	{
		$this->replaceWidgetWithType('async', func_get_args());
	}

	/**
	 * Implementing this method to directly echo in twig template
	 *
	 * @return string
	 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 */
	function __toString()
	{
		return (string) $this->display();
	}

	/**
	 * Display all widgets from this group in correct order.
	 *
	 * @return string
	 */
	public function display()
	{
		ksort($this->widgets);

		$output = '';
		$count = 0;
		foreach ( $this->widgets as $position => $widgets ) {
			foreach ( $widgets as $widget ) {
				$count++;
				$output .= $this->displayWidget($widget);
				if ( $this->count !== $count ) {
					$output .= $this->separator;
				}
			}
		}

		return $this->convertToViewExpression($output);
	}

	/**
	 * Display a widget according to its type.
	 *
	 * @param $widget
	 *
	 * @return mixed
	 */
	protected function displayWidget( $widget )
	{
		$factory = $this->app->make($widget['type'] === 'sync' ? 'flinnt.core.view.widget' : 'flinnt.core.view.async-widget');

		return call_user_func_array([$factory, 'run'], $widget['arguments']);
	}


}
