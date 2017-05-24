<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:33 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Roles;

use Cartalyst\Sentinel\Roles\EloquentRole as SentinelEloquentRole;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentRole
 *
 * @package Flinnt\ACL\Auth\Sentinel\Roles
 */
class EloquentRole extends SentinelEloquentRole
{

	use ACLModalTrait;
	protected $table = TABLE_ADMIN_ROLES; //My Changes

	/**
	 * The Users relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(static::$usersModel, TABLE_ADMIN_ROLE_USERS, 'role_id', 'user_id')->withPivot("inserted")->withTimestamps();
	}

}