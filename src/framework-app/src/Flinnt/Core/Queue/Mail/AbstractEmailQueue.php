<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/11/16
 * Time: 5:49 PM
 */

namespace Flinnt\Core\Queue\Mail;


use Illuminate\Queue\Queue;

/**
 * Class AbstractEmailQueue
 *
 * @package Flinnt\Core\Queue\Mail
 */
abstract class AbstractEmailQueue extends Queue
{

	/**
	 * Create a payload string from the given job and data.
	 *
	 * @param  string $job
	 * @param  mixed  $data
	 * @param  string $queue
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createPayload( $job, $data = '', $queue = null )
	{
		return array_merge($data, ['email_body_html' => (String) $job]);
	}

	/**
	 * Create a typical, "plain" queue payload array.
	 *
	 * @param  string $job
	 * @param  mixed  $data
	 *
	 * @return array
	 */
	protected function createPlainPayload( $job, $data )
	{
		return array_merge($data, ['email_body_html' => (String) $job]);
	}
}