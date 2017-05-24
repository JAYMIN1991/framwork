<?php
namespace Flinnt\Core\Collection;


use InvalidArgumentException;
use OutOfRangeException;

/**
 * Class StronglyTypedCollection
 *
 * @package Flinnt\Core\Collection
 */
class StronglyTypedCollection implements \Countable, \Iterator, \ArrayAccess
{

	/**
	 * @var
	 */
	protected $_container;

	/**
	 * @var
	 */
	protected $_type;

	/**
	 * Constructor
	 *
	 * @param string $typeName name of the Class
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct( $typeName )
	{

		if ( class_exists($typeName) ) {

			$this->_col_type = new \ReflectionClass($typeName);

		}
		else {

			throw new \InvalidArgumentException("The class ${typeName} does not exist.");

		}

	}

	/********* Countable Methods - STARTS **********/

	/**
	 * This method is executed when using the count() function on an object implementing Countable
	 * @return int
	 * @see http://php.net/manual/en/class.countable.php Countable Interface
	 */
	public function count()
	{
		return count($this->_container);
	}


	/********* Countable Methods - ENDS **********/

	/**
	 * Returns the key of the current element.
	 * @return mixed Returns scalar on success, or NULL on failure.
	 * @see http://php.net/manual/en/class.iterator.php Iterator Interface
	 */
	public function key()
	{
		return key($this->_container);
	}

	/**
	 * Moves the current position to the next element.
	 * @return mixed Any returned value is ignored.
	 * @see http://php.net/manual/en/class.iterator.php Iterator Interface
	 */
	public function next()
	{
		return next($this->_container);
	}

	/**
	 * Rewinds back to the first element of the Iterator.
	 * @return mixed Any returned value is ignored.
	 * @see http://php.net/manual/en/class.iterator.php Iterator Interface
	 */
	public function rewind()
	{
		return reset($this->_container);
	}

	/**
	 * This method is called after Iterator::rewind() and Iterator::next() to check if the current position is valid.
	 * @return bool The return value will be casted to boolean and then evaluated. Returns TRUE on success or FALSE on failure.
	 * @see http://php.net/manual/en/class.iterator.php Iterator Interface
	 */
	public function valid()
	{
		return $this->current() !== false;
	}

	/********* Iterator Methods - STARTS **********/

	public function current()
	{
		return current($this->_container);
	}

	/********* Iterator Methods - ENDS **********/

	/********* ArrayAccess Methods - STARTS *********
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */

	public function offsetExists( $offset )
	{

		return isset($this->_container[$offset]);
	}

	/**
	 *
	 * Adds an object to the collection.
	 *
	 * @param  object $object The object to add to the collection
	 *
	 * @return mixed
	 */
	public function add( $object )
	{

		$this->offsetSet($object, NULL);

		return $object;

	}

	/**
	 * Assigns a value to the specified offset.
	 *
	 * @param mixed $object The object to set.
	 * @param null  $offset The offset to assign the value to.
	 *
	 * @throws \InvalidArgumentException
	 * @see http://php.net/manual/en/class.arrayaccess.php ArrayAccess Interface
	 */
	public function offsetSet( $object, $offset = NULL )
	{

		if ( $object instanceof $this->_col_type->name ) {

			if ( is_null($offset) ) {

				$this->_container[] = $object;

			}
			else {

				$this->_container[$offset] = $object;

			}

		}
		else {

			throw new \InvalidArgumentException("Object needs to be a {$this->_col_type->name} instance.");

		}

	}

	/**
	 * Removes all elements from the collection
	 */
	public function clear()
	{

		$this->_container = array();
	}

	/**
	 * Return an object index
	 *
	 * @param $object
	 *
	 * @return int
	 */
	public function indexOf( $object )
	{

		return array_search($object, $this->_container);

	}

	/**
	 * Inserts an object at the specified offset in the collection
	 *
	 * @param $offset offset at which new object should be inserted
	 * @param $object object to add
	 *
	 * @return mixed
	 *
	 * @throws OutOfRangeException If the object is not of the underlying type
	 * @throws InvalidArgumentException If the offset does not exist
	 */
	public function insert( $offset, $object )
	{

		if ( array_key_exists($offset, $this->_container) ) {

			if ( $object instanceof $this->_type->name ) {

				$tempArray = array($object, $this->offsetGet($offset));
				array_splice($this->_container, $offset, 1, $tempArray);
				$this->_container = array_values($this->_container);

			}
			else {

				throw new InvalidArgumentException("Object needs to be a {$this->_type->name} instance.");

			}

		}
		else {

			throw new OutOfRangeException("The index ${offset} does not exists in the collection.");

		}

		return $object;

	}

	/**
	 * This method is executed when checking if offset is empty().
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return bool Returns the value at specified offset.
	 * @see http://php.net/manual/en/class.arrayaccess.php ArrayAccess Interface
	 */
	public function offsetGet( $offset )
	{

		if ( isset($this->_container[$offset]) ) {

			return $this->_container[$offset];

		}
		else {

			return false;

		}
	}

	/**
	 * Removes the specified object from the collection
	 *
	 * @param $object object to remove from collection
	 */
	public function remove( $object )
	{

		if ( $this->contains($object) ) {

			$this->offsetUnset(array_search($object, $this->_container));
			$this->_container = array_values($this->_container);

		}

	}

	/**
	 * Checks if an object belongs to the collection
	 *
	 * @param $object object to search
	 *
	 * @return bool If found returns true, false otherwise
	 */
	public function contains( $object )
	{

		return in_array($object, array_values($this->_container), true);

	}

	/**
	 * Unsets an offset.
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @see http://php.net/manual/en/class.arrayaccess.php ArrayAccess Interface
	 */
	public function offsetUnset( $offset )
	{

		unset($this->_container[$offset]);

		$this->_container = array_values($this->_container);

	}

	/**
	 * Removes the object at the specified offset in the collection
	 *
	 * @param $offset
	 */
	public function removeAt( $offset )
	{

		$this->offsetUnset($offset);
		$this->_container = array_values($this->_container);

	}

	/********* ArrayAccess Methods - ENDS **********/

}