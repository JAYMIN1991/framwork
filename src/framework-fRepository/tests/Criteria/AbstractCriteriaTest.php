<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 28/12/16
 * Time: 7:38 PM
 */

namespace Flinnt\Repository\Test;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Class AbstractCriteriaTest
 * @package Flinnt\Repository\Test
 */
class AbstractCriteriaTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Test if getAttributeName method return proper attribute name.
	 *
	 */
	public function testGetAttributeName()
	{
		$criteria = $this->getMockForAbstractClass(AbstractCriteria::class); //Create the mockable abstract class

		$class =  new ReflectionClass(get_class($criteria));
		$method = $class->getMethod('getAttributeName');
		$method->setAccessible(true);

		$result = $method->invokeArgs($criteria, ["table", "attribute"]); //invoke the getAttributeName method of AbstractCriteria

		$this->assertEquals("table.attribute", $result);
	}
}