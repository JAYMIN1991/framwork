<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/11/16
 * Time: 3:37 PM
 */

namespace Flinnt\ACL\Auth\Sentinel\Users;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelEloquentUser;
use Flinnt\ACL\Auth\Sentinel\Database\ACLModalTrait;

/**
 * Class EloquentUser
 *
 * @package Flinnt\ACL\Auth\Sentinel\Users
 */
class EloquentUser extends SentinelEloquentUser
{

	use ACLModalTrait;

	protected $table = TABLE_ADMIN_USERS;

	protected $loginNames = ['user_login'];

	protected $primaryKey = 'user_id';

	/**
	 * Returns the roles relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(static::$rolesModel, TABLE_ADMIN_ROLE_USERS, 'user_id', 'role_id')->withTimestamps();
	}

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	public function __get( $key )
	{
		if ( $key == "password" ) {
			$key = "user_password_v1";
		}

		return parent::__get($key);
	}


}