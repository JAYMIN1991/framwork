<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 2:26 PM
 */

namespace Flinnt\Core\SMS;


/**
 * Class SMSHandlerBridge
 *
 * @package Flinnt\Core\SMS
 */
class SMSHandlerBridge
{

	/**
	 * @var string
	 */
	protected $to;

	/**
	 * @var \Flinnt\Core\SMS\SMSHandler
	 */
	protected $smsHandler;

	/**
	 * @var string
	 */
	protected $content = '';

	/**
	 * SMSHandlerBridge constructor.
	 *
	 * @param $smsHandler
	 */
	public function __construct( SMSHandler $smsHandler )
	{
		$this->smsHandler = $smsHandler;
	}

	/**
	 * Set the recipients of the message.
	 *
	 * @param $to
	 *
	 * @return $this
	 */
	public function to( $to )
	{
		$this->to = $to;

		return $this;
	}

	/**
	 * Set the recipients of the message when recipients is in array.
	 *
	 * @param array $to
	 *
	 * @return $this
	 */
	public function toArray( array $to )
	{
		$this->to = $this->parseAdresses($to);

		return $this;
	}

	/**
	 * @param $to
	 *
	 * @return array
	 */
	protected function parseAdresses( $to )
	{
		$toArray = $to;
		if ( is_string($to) ) {
			$toArray = explode(",", $to);
		}

		$newTo = array();
		foreach ( $toArray as $to ) {
			if ( empty($to) ) {
				continue;
			}
			$newTo[] = $to;
		}

		return $newTo;
	}

	/**
	 * Send the new SMSNotifiable message instance.
	 *
	 * @param \Flinnt\Core\SMS\SMSNotifiable $smsNotifiable
	 *
	 * @return mixed
	 */
	public function send( SMSNotifiable $smsNotifiable )
	{
		return $this->smsHandler->send($this->fill($smsNotifiable));
	}

	/**
	 * populate the SMSNotifiable with address.
	 *
	 * @param \Flinnt\Core\SMS\SMSNotifiable $smsNotifiable
	 *
	 * @return mixed
	 */
	protected function fill( SMSNotifiable $smsNotifiable )
	{
		return $smsNotifiable->to($this->to);
	}

	/**
	 * Queue the new SMSNotifiable message instance
	 *
	 * @param \Flinnt\Core\SMS\SMSNotifiable $smsNotifiable
	 *
	 * @return mixed
	 */
	public function queue( SMSNotifiable $smsNotifiable )
	{
		return $this->smsHandler->queue($this->fill($smsNotifiable));
	}

	/**
	 * Delay the new SMSNotifiable message instance.
	 *
	 * @param                                $delay
	 * @param \Flinnt\Core\SMS\SMSNotifiable $smsNotifiable
	 *
	 * @return mixed
	 */
	public function later( $delay, SMSNotifiable $smsNotifiable )
	{
		return $this->smsHandler->later($delay, $this->fill($smsNotifiable));
	}

}