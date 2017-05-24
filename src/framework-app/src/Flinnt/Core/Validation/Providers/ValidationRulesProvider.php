<?php

namespace Flinnt\Core\Validation\Providers;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class ValidationRulesProvider
 *
 * @package Flinnt\Core\Validation\Providers
 */
class ValidationRulesProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Validator::extend('alpha_space', function ( $attribute, $value, $parameters, $validator ) {
			return preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]+$/', $value);
		}, 'The :attribute may only contain letters and spaces.');

		Validator::extend('no_tags', function ( $attribute, $value, $parameters, $validator ) {
			return $value == strip_tags($value);
		}, 'The :attribute may contain invalid inputs.');

		Validator::extend('institute', function ($attribute, $value, $parameters, $validator){
			$institute = DB::table(TABLE_USERS)->select('user_id')->where(function ( $query ) use ( $value ) {
				/** @var Builder $query */
				$query->whereRaw('IFNULL(' . TABLE_USERS . '.user_plan_id, 0) > 0')
				      ->whereRaw("IFNULL(" . TABLE_USERS . ".user_school_name,'') <> ''")
				      ->where(TABLE_USERS . '.user_institute_verified', '=', 1)
				      ->where(TABLE_USERS . '.user_plan_expired', '=', 0)
				      ->where(TABLE_USERS . '.user_plan_cancelled', '=', 0)
				      ->where(TABLE_USERS . '.user_plan_verified', '=', 1)
				      ->where('user_id', '=', $value);
			})->first();

			if (isset($institute['user_id']) && $institute['user_id'] > 0) {
				return true;
			}

			return false;
		}, trans('validation.exists'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
