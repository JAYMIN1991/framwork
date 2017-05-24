<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 11:38 AM
 */

namespace Flinnt\Core\Contracts\SMS;


use Flinnt\Core\SMS\SMSHandler;
use Illuminate\Contracts\Queue\Factory as Queue;

/**
 * Interface SMSNotifiable
 *
 * @package Flinnt\Core\Contracts\SMS
 */
interface SMSNotifiable
{
	/**
	 * Send the message using given SMSHandler
	 *
	 * @param \Flinnt\Core\SMS\SMSHandler $smsHandler
	 * @return mixed
	 */
	public function send(SMSHandler $smsHandler);

	/**
	 * Queue the given message
	 *
	 * @param \Illuminate\Contracts\Queue\Factory $queue
	 * @return mixed
	 */
	public function queue(Queue $queue);

	/**
	 * Deliver the queued message after given time delay
	 * @param \DateTime|int $delay
	 * @param \Illuminate\Contracts\Queue\Factory $queue
	 * @return mixed
	 */
	public function later($delay, Queue $queue);

	/**
	 * Send SMS immediately based on parameters specified.
	 *
	 * @param array $recipients Associative array of email address and name
	 * @return self
	 */
	public function sendNow(array $recipients = null);

	/**
	 * Prepare SMS based on parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of email address and name
	 * @return self
	 */
	public function saveInQueue(array $recipients = null);

	/**
	 * Set the parameter to prepare SMS content.
	 *
	 * @param array $parameters
	 * @return self
	 */
	public function setParameters(array $parameters);

	/**
	 *
	 * Get the parameter used to prepare SMS content.
	 * @return array
	 */
	public function getParameters();

	/**
	 * Process the sms.
	 * 
	 * @param array|null $recipients
	 * @param bool $saveInQueue
	 * @param int $delay
	 *
	 */
	public function processSMS(array $recipients = null, $saveInQueue = false, $delay = 0);

}