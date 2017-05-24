<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 22/11/16
 * Time: 6:01 PM
 */

namespace Flinnt\BackOffice\Support;

use App;
use Carbon\Carbon;
use Config;
use Request;
use Session;

/**
 * Class BackOfficeHelper
 * @package Flinnt\BackOffice\Support
 */
class Helpers {

	/**
	 * Convert date in raw format to timestamp
	 *
	 * @param string|null $date
	 * @param string      $format
	 *
	 * @return int Returns timestamp
	 */
	function dateToTimestamp( $date = null, $format = 'input_date_format' ) {

		// set format available from parameter
		if ( $format == 'input_date_format' ) {
			$format = $this->getConfigTransValue($format);
		}

		// Check for date is available or not
		if ( empty($date) ) {
			// Create timestamp directly
			$timestamp = Carbon::today()->timestamp;
		}else{
			// Create timestamp from format
			$timestamp = Carbon::createFromFormat($format, $date)->timestamp;
		}

		return $timestamp;
	}

	/**
	 * Convert date and time in raw format to timestamp
	 *
	 * @param int|null $datetime
	 * @param string   $format
	 *
	 * @return int Returns timestamp
	 */
	function datetimeToTimestamp( $datetime = null, $format = 'input_date_time_format' ) {

		// set format available from parameter
		if ( $format == 'input_date_time_format' ) {
			$format = $this->getConfigTransValue($format);
		}

		if ( empty($datetime) ) {
			// Create timestamp directly
			$timestamp = Carbon::now()->timestamp;
		}else{
			// Create timestamp from format
			$timestamp = Carbon::createFromFormat($format, $datetime)->timestamp;
		}

		return $timestamp;
	}

	/**
	 * Get Date from timestemp
	 *
	 * @param int    $timestemp timestamp data from which date will be retrieved
	 * @param string $format    output date format
	 *
	 * @return Carbon
	 */
	function timestempToDate( $timestemp = null, $format = 'output_date_format' ) {

		// set format available from parameter
		if ( $format == 'output_date_format' ) {
			$format = $this->getConfigTransValue($format);
		}

		/*
		 * Set toString format so directly echo or print will give
		 * String format data
		 */
		Carbon::setToStringFormat($format);

		if ( empty($timestemp) || ! is_numeric($timestemp) ) {

			// generate date from current time
			$date = Carbon::now(Config::get('app.timezone'));
		}else{

			// generate date from timestamp from parameter
			$date = Carbon::createFromTimestamp($timestemp, Config::get('app.timezone'));
		}

		return $date;
	}

	/**
	 * Get Datetime from timestemp
	 *
	 * @param int    $timestemp timestamp data from which datetime will be retrieved
	 * @param string $format    output datetime format
	 *
	 * @return Carbon
	 */
	function timestempToDatetime( $timestemp = null, $format = 'output_date_time_format' ) {
		if ( $format == 'output_date_time_format' ) {
			$format = $this->getConfigTransValue($format);
		}

		/*
		 * Set toString format so directly echo or print will give
		 * String format data
		 */
		Carbon::setToStringFormat($format);

		if ( empty($timestemp) || ! is_numeric($timestemp) ) {

			// generate date from current time
			$datetime = Carbon::now(Config::get('app.timezone'));
		} else {

			// generate date from timestamp from parameter
			$datetime = Carbon::createFromTimestamp($timestemp, Config::get('app.timezone'));
		}

		return $datetime;
	}

	/**
	 * Check if logged in user is an admin  or sales employee via defined constant
	 *
	 * @return bool
	 */
	function isLoginUserAnAdmin() {

		$isAdmin = false;

		/* check if logged in user is admin or sales employee by checking logged in
		   user id in defined constant */
		if ( defined("INST_CALL_VISIT_ADMIN_IDS") && INST_CALL_VISIT_ADMIN_IDS != '' ) {

			$admin_ids = explode(",", INST_CALL_VISIT_ADMIN_IDS);

			if ( in_array(Session::get('user_id'), $admin_ids) ) {
				$isAdmin = true;
			}
		} elseif ( Session::get('user_id') == BACKOFFICE_ADMIN_ID ) {
			$isAdmin = true;
		}

		return $isAdmin;
	}

	/**
	 * Returns active request ip address and false while on console
	 *
	 * @param bool $singleIp If true returns only single IP address
	 *
	 * @return string Request IP address
	 */
	function getIPAddress( $singleIp = false ) {

		if ( App::runningInConsole() ) {
			if ( getenv('HTTP_X_FORWARDED_FOR') ) {
				$env_ip = getenv('HTTP_X_FORWARDED_FOR');
			} elseif ( getenv('HTTP_CLIENT_IP') ) {
				$env_ip = getenv('HTTP_CLIENT_IP');
			} else {
				$env_ip = getenv('REMOTE_ADDR');
			}

			$multipleIps = explode(",", $env_ip);

			$count = count($multipleIps);
			if ( $count > 0 ) {
				if ( $singleIp ) {
					$ipAddress = $multipleIps[$count - 1];
				} else {
					$ipAddress = $env_ip;
				}
			} else {
				$ipAddress = $env_ip;
			}

		} else {

			$multipleIps = Request::ips();
			$count = count($multipleIps);
			if ( $count > 0 ) {
				if ( $singleIp ) {
					$ipAddress = $multipleIps[$count - 1];
				} else {
					$ipAddress = implode(',', $multipleIps);
				}
			} else {
				$ipAddress = Request::ip();
			}

		}

		return $ipAddress;
	}

	/**
	 * Returns new date object with specified output format data
	 *
	 * @param string $outputDateFormat output format of the date, default format is as per  'mysql_date_format' key from config.php
	 * @param string $date date from which new carbon date object is created, default date is now
	 * @param string $inputDateFormat format of the specified date, default is as per 'input_date_format' key from config.php
	 *
	 * @return \Carbon\Carbon Returns Carbon date object
	 */
	public function getDate( $outputDateFormat = 'mysql_date_format', $date = 'now', $inputDateFormat = 'input_date_format') {

		if ( $outputDateFormat == 'mysql_date_format' ) {
			$outputDateFormat = $this->getConfigTransValue($outputDateFormat);
		}

		/*
		 * Set toString format so directly echo or print will give
		 * String format data
		 */
		Carbon::setToStringFormat($outputDateFormat);

		/* No custom date is applied, so returns date object of current date */
		if ( $date == 'now' ) {
			return Carbon::now(Config::get('app.timezone'));
		} else {

			/* Get the actual format of date */
			if ( $inputDateFormat == 'input_date_format' ) {
				$inputDateFormat = $this->getConfigTransValue($inputDateFormat);
			}

			return Carbon::createFromFormat($inputDateFormat, $date, Config::get('app.timezone'));
		}
	}

	/**
	 * Convert value from trans key
	 * @param string $format key of language variable
	 *
	 * @return bool|string|\Symfony\Component\Translation\TranslatorInterface Returns trans value or false
	 */
	private function getConfigTransValue($format) // TODO:: Improve this function, need to be commited to framework
	{
		if(isset($format))
		{
			return trans("shared::config." . $format);
		}
		return false;
	}

}