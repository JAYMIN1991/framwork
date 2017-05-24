<?php

namespace Flinnt\Core\View\Widgets\Test;

use Flinnt\Core\View\Widgets\Test\Support\TestApplicationWrapper;
use Flinnt\Core\View\Widgets\Test\Support\TestCase;
use Flinnt\Core\View\Widgets\WidgetGroup;
use Flinnt\Core\View\Widgets\WidgetGroupCollection;

class WidgetGroupCollectionTest extends TestCase
{

	/**
	 * @var WidgetGroupCollection
	 */
	protected $collection;

	public function setUp()
	{
		$this->collection = new WidgetGroupCollection(new TestApplicationWrapper());
	}

	public function testItGrantsAccessToWidgetGroup()
	{
		$groupObject = $this->collection->group('sidebar');

		$expectedObject = new WidgetGroup('sidebar', new TestApplicationWrapper());

		$this->assertEquals($expectedObject, $groupObject);
	}
}
