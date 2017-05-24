<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 11:36 AM
 */

namespace Flinnt\Core\SMS;

use Carbon\Carbon;
use DateTime;
use Flinnt\Core\Contracts\SMS\SMSNotifiable as SMSNotifiableContract;
use Flinnt\Core\SMS\Exceptions\InvalidSMSException;
use Flinnt\Core\SMS\Facades\LaxusSMS;
use Flinnt\Core\SMS\Facades\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Factory as Queue;
use Illuminate\Container\Container;
use Illuminate\Queue\SerializesModels;

/**
 * Class SMSNotifiable
 *
 * @package Flinnt\Core\SMS
 */
abstract class SMSNotifiable implements SMSNotifiableContract
{

	use Queueable, SerializesModels;

	/**
	 * constant for verified sms route
	 */
	const SMS_ROUTE_VERIFIED = SMS::class;
	/**
	 *constant for unverified sms route
	 */
	const SMS_ROUTE_UNVERIFIED = LaxusSMS::class;
	/**
	 * @var array
	 */
	protected $parameters = [];
	/**
	 * Array of recipients address
	 *
	 * @var array
	 */
	protected $to = [];
	/**
	 * Content of message.
	 *
	 * @var string
	 */
	protected $content = '';
	/**
	 * @var string
	 */
	protected $smsRoute;

	/**
	 * SMSNotifiable constructor.
	 *
	 * @param array|null $parameters
	 */
	public function __construct( array $parameters = null )
	{
		$this->parameters = $parameters;
		$this->onConnection("sms");
		$this->setSMSRoute(self::SMS_ROUTE_VERIFIED);
	}


	/**
	 * /**
	 * Send the message using given SMSHandler
	 *
	 * @param \Flinnt\Core\SMS\SMSHandler $smsHandler
	 *
	 * @return string
	 */
	public function send( SMSHandler $smsHandler )
	{
		$result = array();
		Container::getInstance()->call([$this, 'build']);

		foreach ( $this->to as $key => $value ) {
			$result[] = $smsHandler->send($this->content, $value);
		}

		return json_encode($result);
	}

	/**
	 * Queue the given message
	 *
	 * @param \Illuminate\Contracts\Queue\Factory $queue
	 *
	 * @return bool
	 * @throws \Exception;
	 */
	public function queue( Queue $queue )
	{
		$connection = property_exists($this, 'connection') ? $this->connection : null;
		Container::getInstance()->call([$this, 'build']);

		$result = array();
		//TODO: Add transaction here as well as in other queue methods.

		foreach ( $this->to as $key => $value ) {
			$result[]['to'] = $value;
			$result[]['response'] = $queue->connection($connection)->push($this->content, $this->buildDabaseRecord(0, $value));
		}

		return json_encode($result);
	}

	/**
	 * Create the database record based on passed parameters.
	 *
	 * @param $delay
	 * @param $to
	 *
	 * @return array
	 * @throws InvalidSMSException
	 */
	protected function buildDabaseRecord( $delay, $to )
	{

		$scheduleDate = 0;

		if ( $delay > 0 ) {
			$scheduleDate = $this->getAvailableAt($delay);
		}

		if ( empty($to) || trim($to) == '' ) {
			throw new InvalidSMSException("Please provide valid recipient mobile number");
		}

		return ['mobile_no' => $to, 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => $scheduleDate, 'sms_mode' => $this->queue, 'user_id' => \Request::get('user_id', 0),];
	}

	/**
	 * Get the "available at" UNIX timestamp.
	 *
	 * @param  \DateTime|int $delay
	 *
	 * @return int
	 */
	protected function getAvailableAt( $delay )
	{
		$availableAt = $delay instanceof DateTime ? $delay : Carbon::now()->addSeconds($delay);

		return $availableAt->getTimestamp();
	}

	/**
	 * Deliver the queued message after given time delay
	 *
	 * @param \DateTime|int                       $delay
	 * @param \Illuminate\Contracts\Queue\Factory $queue
	 *
	 * @return mixed
	 */
	public function later( $delay, Queue $queue )
	{
		$connection = property_exists($this, 'connection') ? $this->connection : null;
		Container::getInstance()->call([$this, 'build']);

		$result = array();
		//TODO: Add transaction here as well as in other queue methods.
		foreach ( $this->to as $key => $value ) {
			$result[]['to'] = $value;
			$result[]['response'] = $queue->connection($connection)->push($this->content, $this->buildDabaseRecord($delay, $value));
		}

		return json_encode($result);
	}

	/**
	 * Add the recipient/s phone number to message.
	 * Handle the single phone number as well as comma separated phone number.
	 *
	 * @param string $to
	 *
	 * @return \Flinnt\Core\SMS\SMSNotifiable
	 */
	public function to( $to )
	{
		return $this->setAdresses($to);
	}

	/**
	 * Set the address to message.
	 *
	 * @param string $to
	 *
	 * @return $this
	 */
	protected function setAdresses( $to )
	{
		if ( is_string($to) ) {
			$this->to = $this->parseAdresses($to);
		}
		else {
			$this->to = $to;
		}

		return $this;
	}

	/**
	 * Parse the address. Create array from comma separated string of phone numbers
	 *
	 * @param string $to
	 *
	 * @return array
	 */
	protected function parseAdresses( $to )
	{
		$toArray = explode(",", $to);
		$to = array();
		foreach ( $toArray as $address ) {
			if ( empty($address) ) {
				continue;
			}
			$to[] = $address;
		}

		return $to;
	}

	/**
	 * Add the recipient/s phone number to message.
	 * Handle the single phone number(must pass as array) as well as array of phone number.
	 *
	 * @param array $to
	 *
	 * @return \Flinnt\Core\SMS\SMSNotifiable
	 */
	public function toArray( array $to )
	{
		return $this->setAdresses($to);
	}

	/**
	 * Send SMS immediately based on parameters specified.
	 *
	 * @param array $recipients Associative array of mobile number
	 *
	 * @return self
	 */
	public function sendNow( array $recipients = null )
	{
		$this->processSMS($recipients);

		return $this;
	}

	/**
	 * Process the sms.
	 *
	 * @param array|null $recipients
	 * @param bool       $saveInQueue
	 * @param int        $delay
	 *
	 */
	abstract public function processSMS( array $recipients = null, $saveInQueue = false, $delay = 0 );

	/**
	 * Prepare SMS based on parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of mobile number
	 *
	 * @return self
	 */
	public function saveInQueue( array $recipients = null )
	{
		$this->processSMS($recipients, true);

		return $this;
	}

	/**
	 * Prepare SMS based on parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of mobile number
	 * @param int   $delay      Delay in seconds
	 *
	 * @return self
	 */
	public function sendLater( array $recipients = null, $delay )
	{
		$this->processSMS($recipients, false, $delay);

		return $this;
	}

	/**
	 *Get the parameter used to prepare SMS content.
	 *
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * Set the parameter to prepare SMS content.
	 *
	 * @param array $parameters
	 *
	 * @return self
	 */
	public function setParameters( array $parameters )
	{
		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * Get the route of sms
	 *
	 * @return string
	 */
	public function getSMSRoute()
	{
		return $this->smsRoute;
	}

	/**
	 * Set the route for SMS
	 *
	 * @param string $route
	 *
	 * @return self $this
	 */
	public function setSMSRoute( $route )
	{
		$this->smsRoute = $route;

		if ( $this->smsRoute == self::SMS_ROUTE_UNVERIFIED ) {
			$this->queue = "laxussms";
		}
		else {
			$this->queue = "default";
		}

		return $this;
	}

	/**
	 * Get the sms template.
	 *
	 * @param       $template
	 * @param array ...$data
	 *
	 * @return self
	 */
	public function setSMSTemplate( $template, ...$data )
	{
		$this->content = vsprintf($template, $data);

		return $this;
	}

	/**
	 * Build the message.
	 *
	 * @return void
	 */
	abstract public function build();

	/**
	 * Process the sms logic and send the sms.
	 *
	 * @param array $recipients Associative array of mobile number
	 * @param bool  $saveInQueue
	 * @param int   $delay      Delay in seconds
	 *
	 * @return self
	 */
	protected function sendSMS( array $recipients, $saveInQueue = false, $delay = 0 )
	{
		if ( $saveInQueue ) {
			$sms = call_user_func(array($this->smsRoute, 'toArray'), $recipients);
			$sms->queue($this);
		}
		elseif ( ! empty($delay) && $delay != 0 ) {
			$sms = call_user_func(array($this->smsRoute, 'toArray'), $recipients);
			$sms->later($delay, $this);
		}
		else {
			$sms = call_user_func(array($this->smsRoute, 'toArray'), $recipients);
			$sms->send($this);
		}

		return $this;
	}

}