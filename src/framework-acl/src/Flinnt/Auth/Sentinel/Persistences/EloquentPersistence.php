<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:27 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Persistences;

use Cartalyst\Sentinel\Persistences\EloquentPersistence as SentinelEloquentPersistence;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentPersistence
 *
 * @package Flinnt\ACL\Auth\Sentinel\Persistences
 */
class EloquentPersistence extends SentinelEloquentPersistence
{

	use ACLModalTrait;

	protected $table = TABLE_ADMIN_PERSISTENCES; //My Changes

}