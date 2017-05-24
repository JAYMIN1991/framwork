<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/11/16
 * Time: 7:05 PM
 */

namespace Flinnt\Core\SMS;

use Flinnt\Core\Contracts\SMS\SMSNotifiable as SMSNotifiableContract;
use Flinnt\Core\Contracts\SMS\Transport\ITransport;
use Flinnt\Core\SMS\Transport\MobisoftTransport;
use Illuminate\Contracts\Queue\Factory;
use Illuminate\Events\Dispatcher;

/**
 * Class SMSHandler
 *
 * @package Flinnt\Core\SMS
 */
class SMSHandler
{

	/**
	 * @var array
	 */
	protected $to;

	/**
	 * @var String
	 */
	protected $content;

	/**
	 * @var MobisoftTransport
	 */
	protected $gateway;

	/**
	 * @var Dispatcher
	 */
	protected $events;


	/**
	 * @var Factory
	 */
	protected $queue;

	/**
	 * SMSHandler constructor.
	 *
	 * @param ITransport                    $gateway
	 * @param \Illuminate\Events\Dispatcher $events
	 */
	public function __construct( ITransport $gateway, Dispatcher $events )
	{
		$this->gateway = $gateway;
		$this->events = $events;
	}

	/**
	 * Instantly send the message.
	 *
	 * @param SMSNotifiableContract|String $content
	 * @param null|string                  $to
	 *
	 * @return mixed
	 */
	public function send( $content, $to = null )
	{
		if ( $content instanceof SMSNotifiableContract ) {
			return $content->send($this);
		}

		return $this->gateway->send($content, $to);
	}

	/**
	 * Queue the given message.
	 *
	 * @param string $content
	 *
	 * @return mixed
	 */
	public function queue( $content )
	{
		if ( $content instanceof SMSNotifiableContract ) {
			return $content->queue($this->queue);
		}

		return null;
	}

	/**
	 * Queue the given message.
	 *
	 * @param        $delay
	 * @param string $content
	 *
	 * @return mixed
	 */
	public function later( $delay, $content )
	{
		if ( $content instanceof SMSNotifiableContract ) {
			return $content->later($delay, $this->queue);
		}

		return null;
	}


	/**
	 * Begin the process of messaging a SMSNotifiable class instance.
	 *
	 * @param string $to
	 *
	 * @return SMSHandlerBridge
	 */
	public function to( $to )
	{
		return (new SMSHandlerBridge($this))->to($to);
	}

	/**
	 * Begin the process of messaging a SMSNotifiable class instance.
	 *
	 * @param array $to
	 *
	 * @return SMSHandlerBridge
	 */
	public function toArray( array $to )
	{
		return (new SMSHandlerBridge($this))->toArray($to);
	}

	/**
	 * Set the queue manager instance.
	 *
	 * @param \Illuminate\Contracts\Queue\Queue $queue
	 *
	 * @return self
	 */
	public function setQueue( $queue )
	{
		$this->queue = $queue;

		return $this;
	}

	/**
	 * @return \Flinnt\Core\SMS\Transport\MobisoftTransport
	 */
	public function getGateway()
	{
		return $this->gateway;
	}

	/**
	 * Set the recipient for message
	 *
	 * @param string|array $to
	 *
	 * @return array
	 */
	protected function setAdresses( $to )
	{
		if ( is_string($to) ) {
			return $this->parseAdresses($to);
		}

		return $to;
	}

	/**
	 * Parse the address. Convert the address in array.
	 *
	 * @param string $to
	 *
	 * @return array
	 */
	protected function parseAdresses( $to )
	{
		return explode(",", $to);
	}
}