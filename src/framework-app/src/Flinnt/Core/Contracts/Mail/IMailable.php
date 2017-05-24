<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/11/16
 * Time: 2:28 PM
 */

namespace Flinnt\Core\Contracts\Mail;


/**
 * Interface IMailable
 *
 * @package Flinnt\Core\Contracts\Mail
 */
interface IMailable
{

	/**
	 * Send email immediately based on parameters specified.
	 *
	 * @param array $recipients Associative array of email address and name
	 *
	 */
	public function sendNow( array $recipients = null );

	/**
	 * Prepare email based on parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of email address and name
	 *
	 */
	public function saveInQueue( array $recipients = null );

	/**
	 * Set the parameter to prepare email content.
	 *
	 * @param array $parameters
	 *
	 * @return self
	 */
	public function setParameters( array $parameters );

	/**
	 *
	 * Get the parameter used to prepare email content.
	 * @return array
	 */
	public function getParameters();

}