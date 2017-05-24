<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:35 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Throttle;

use Cartalyst\Sentinel\Throttling\EloquentThrottle as SentinelEloquentThrottle;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentThrottle
 *
 * @package Flinnt\ACL\Auth\Sentinel\Throttle
 */
class EloquentThrottle extends SentinelEloquentThrottle
{

	use ACLModalTrait;

	protected $table = TABLE_ADMIN_THROTTLES;
}