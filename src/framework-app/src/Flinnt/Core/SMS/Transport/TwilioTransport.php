<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/11/16
 * Time: 4:07 PM
 */

namespace Flinnt\Core\SMS\Transport;

use Flinnt\Core\Contracts\SMS\Transport\ITransport;

/**
 * Class TwilioTransport
 *
 * @package Flinnt\Core\SMS\Transport
 */
class TwilioTransport implements ITransport
{

	protected $accoundSid;

	protected $authToken;

	protected $from;

	/**
	 * @var \Services_Twilio
	 */
	protected $twilio;

	/**
	 * TwilioTransport constructor.
	 *
	 * @param $accoundSid
	 * @param $authToken
	 * @param $from
	 */
	public function __construct( $accoundSid, $authToken, $from )
	{
		$this->accoundSid = $accoundSid;
		$this->authToken = $authToken;
		$this->from = +15005550006;
		$this->initialize();
	}

	protected function initialize()
	{
		if ( ! class_exists('\Services_Twilio') ) {
			throw new \Exception("Twilio package is not installed. Install it using 'composer require twilio\\sdk'");
		}

		$this->twilio = new \Services_Twilio($this->accoundSid, $this->authToken);
	}

	/**
	 * @param string $content
	 * @param string $to
	 *
	 * @return mixed|void
	 */
	public function send( $content, $to )
	{
		$to = "+91" . $to;
		var_dump($this->twilio->account->messages->sendMessage($this->from, $to, trim($content)));
//		return $this->twilio->account->messages->sendMessage($this->from, $to, trim($content));
	}

	public function sendToMultiple()
	{
		// TODO: Implement sendToMultiple() method.
	}

	/**
	 * @param $content
	 * @param $mobileNo
	 *
	 * @return mixed|void
	 */
	public function addToMultipleSend( $content, $mobileNo )
	{
		// TODO: Implement addToMultipleSend() method.
	}

	public function resetMultiple()
	{
		// TODO: Implement resetMultiple() method.
	}
}