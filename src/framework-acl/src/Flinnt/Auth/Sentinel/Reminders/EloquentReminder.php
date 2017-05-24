<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:29 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Reminders;

use Cartalyst\Sentinel\Reminders\EloquentReminder as SentinelEloquentReminders;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentReminder
 *
 * @package Flinnt\ACL\Auth\Sentinel\Reminders
 */
class EloquentReminder extends SentinelEloquentReminders
{

	use ACLModalTrait;

	protected $table = TABLE_ADMIN_REMINDERS; //My Changes

}