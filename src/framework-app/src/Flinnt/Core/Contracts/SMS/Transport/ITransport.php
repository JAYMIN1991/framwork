<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/11/16
 * Time: 3:28 PM
 */

namespace Flinnt\Core\Contracts\SMS\Transport;


/**
 * Interface ITransport
 *
 * @package Flinnt\Core\Contracts\SMS\Transport
 */
interface ITransport
{
	/**
	 * Send the sms through specified gateway
	 * 
	 * @param string $content
	 * @param string $mobileNo
	 * @return mixed
	 */
	public function send($content, $mobileNo);

	public function sendToMultiple();

	/**
	 * @param $content
	 * @param $mobileNo
	 * @return mixed
	 */
	public function addToMultipleSend($content, $mobileNo);

	public function resetMultiple();
	
}