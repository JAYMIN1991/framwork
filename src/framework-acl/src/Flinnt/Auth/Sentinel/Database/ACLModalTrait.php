<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 6:55 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Database;


/**
 * Class ACLModalTrait
 *
 * @package Flinnt\ACL\Auth\Sentinel\Database
 */
trait ACLModalTrait
{

	protected function updateTimestamps()
	{
		$time = $this->freshTimestamp();

		if ( ! $this->isDirty(static::UPDATED_AT) ) {
			$this->{self::UPDATED_AT} = $time;

			$this->updated = $time->getTimestamp();
		}

		if ( ! $this->exists ) {
			$this->{self::CREATED_AT} = $time;
			$this->inserted = $time->getTimestamp();
		}
	}

}