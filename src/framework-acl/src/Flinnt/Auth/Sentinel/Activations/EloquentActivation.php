<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:22 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Activations;

use Cartalyst\Sentinel\Activations\EloquentActivation as SentinelEloquentActivation;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentActivation
 *
 * @package Flinnt\ACL\Auth\Sentinel\Activations
 */
class EloquentActivation extends SentinelEloquentActivation
{

	use ACLModalTrait;

	protected $table = TABLE_ADMIN_ACTIVATIONS;

}