<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/10/16
 * Time: 2:51 PM
 */

namespace Flinnt\Core\DBLog;

use DB;
use Exception;
use Request;

/**
 * Class DBLog
 * @package App\Modules\Applog\Helper
 */
class DBLog {

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * DBLog constructor.
	 */
	private function __construct() {
	}

	/**
	 * @return DBLog
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * used for logging. Update the log to database.
	 *
	 * @param  String       $module  Specify module name by using constant. like, LOG_MODULE_SALES_TEAM
	 * @param  int|null     $moduleId  Specify module id or null
	 * @param  String       $action Specify module action. like, "search"
	 * @param  String       $uri  Specify of the page, use $request->getRequestUri()
	 * @param  int          $userId Pass user_id of active user, or pass 0.
	 * @param  string|array $info Specify array or string of Extra parameters you want to log
	 *
	 * @return bool|int This function will return false in case of failed, and id of log entry
	 */
	public function save( $module, $moduleId, $action, $uri, $userId, ...$info ) {
		$data = array();
		if ( ! empty($userId) ) {
			$data['user_id'] = $userId;
		} else {
			$data['user_id'] = 0;
		}

		if ( ! empty($info) ) {
			/*Argument array is passed as array on 0th element as per ... operator */
			$info = $this->prepare_extra($info[0]);
		} else {
			$info = "";
		}

		$data['user_ip'] = Request::ip();
		$data['module_id'] = $moduleId;
		$data['module_type'] = $module;
		$data['action'] = $action;
		$data['url'] = $uri;   //$this->routePath($url);
		$data['info'] = $this->prepare_extra($info);
		$data['device_type'] = "BACKOFFICE"; //TODO: consult with Urvish sir, how to get device type.
		$data["user_datetime"] = time();
		try {

			return DB::table(TABLE_BACKOFFICE_LOG)->insertGetId($data);
		}
		catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * @param $extra_information
	 *
	 * @return string
	 */
	private function prepare_extra( $extra_information ) {
		$out = '';
		if ( is_array($extra_information) ) {

			foreach ( $extra_information as $k => $v ) {
				$out .= $k . LOG_EQUAL_TO . ($this->not_null($v) ? $this->prepare_extra($v) : LOG_EMPTY_VALUE) . LOG_SEPARATOR;
			}
			/* new logic for solving trimming issue of last character */
			/* get last string */
			$last_string = substr($out, -strlen(LOG_SEPARATOR), strlen(LOG_SEPARATOR));

			/* if last string is NEXT string then trim it */
			if ( $last_string == LOG_SEPARATOR ) {
				$remaining_string_length = strrpos($out, LOG_SEPARATOR, -strlen(LOG_SEPARATOR));
				$out = substr($out, 0, $remaining_string_length);
			}
			/* new logic for solving trimming issue of last character */
		} else {
			$out = ($this->not_null($extra_information) ? $extra_information : LOG_EMPTY_VALUE);
		}

		return $out;
	}

	/**
	 * Check if value is not null
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	private function not_null( $value ) {
		if ( is_array($value) ) {
			if ( sizeof($value) > 0 ) {
				return true;
			} else {
				return false;
			}
		} else {
			if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0) ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 *  Magic method to prevent cloning of object
	 */
	private function __clone() {
	}

	/**
	 * Intentionally created this private. So that there won't be second instance of this class.
	 */
	private function __wakeup() {
	}

	/**
	 *  Convert Path into relative
	 *
	 * @param $uri
	 *
	 * @return string
	 */
	private function routePath( $uri ) {
		return preg_replace('/^' . preg_quote(url('/'), '/') . '/', '', $uri) . '/';
	}
}