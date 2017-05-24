<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/11/16
 * Time: 5:36 PM
 */

namespace Flinnt\Core\Contracts\Mail;

use Carbon\Carbon;
use DateTime;
use Flinnt\Core\Mail\Facades\LaxusMailFacade;
use Flinnt\Core\Queue\Mail\Exceptions\InvalidMailException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Queue\Factory as Queue;
use Illuminate\Mail\Mailable as LaravelMailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Class Mailable
 *
 * @package Flinnt\Core\Contracts\Mail
 */
abstract class Mailable extends LaravelMailable implements IMailable
{

	use Queueable, SerializesModels;

	/**
	 * Constant to set the mail route for emails which are sent to verified users
	 */
	const MAILER_ROUTE_VERIFIED = Mail::class;
	/**
	 * Constant to set the mail route for emails which are sent to non verified users
	 */
	const MAILER_ROUTE_NON_VERIFIED = LaxusMailFacade::class;
	/**
	 * Parameters used to process the email
	 *
	 * @var array
	 */
	protected $parameters;
	/**
	 * Route used to send the email.
	 *
	 * @var string
	 */
	protected $mailRoute;

	/**
	 * CustomAbstractMailable constructor.
	 *
	 * @param array $parameters
	 */
	public function __construct( array $parameters = [] )
	{
		$this->parameters = $parameters;
		$this->connection = "email";
		$this->setMaileRoute(self::MAILER_ROUTE_VERIFIED);
	}

	/**
	 * Set the route for emails
	 *
	 * @param string $route
	 *
	 * @return self $this
	 */
	public final function setMaileRoute( $route )
	{

		$this->mailRoute = $route;

		if ( $this->mailRoute == self::MAILER_ROUTE_NON_VERIFIED ) {
			$this->queue = "laxusmail";
		}
		else {
			$this->queue = "default";
		}

		return $this;
	}

	/**
	 * Send the message using the given mailer.
	 *
	 * @param  \Illuminate\Contracts\Mail\Mailer $mailer
	 *
	 * @return void
	 */
	public function send( MailerContract $mailer )
	{
		parent::send($mailer);
		$this->to = [];
	}

	/**
	 * Queue the message for sending.
	 *
	 * @param  \Illuminate\Contracts\Queue\Factory $queue
	 *
	 * @return mixed
	 */
	public function queue( Queue $queue )
	{
		$connection = property_exists($this, 'connection') ? $this->connection : null;
		$this->build();
		$pushedData = $queue->connection($connection)->push($this->createView($this->buildView(), $this->buildViewData()), $this->buildDabaseRecord(0, $this));
		// reset recipient list to an empty array
		$this->to = [];

		return $pushedData;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	abstract public function build();

	/**
	 * Build the html view from view name and data.
	 *
	 * @param String $view Name of the template file.
	 * @param array  $data template parameters along with values.
	 *
	 * @return string Returns html generated from template file.
	 */
	protected function createView( $view, $data )
	{
		$data = array_merge($data, $this->getPreDefinedData());
		$content = \View::make($view, $data);

		return $content->render();
	}

	/**
	 * Returns the pre defined data used to generate the email.
	 *
	 * @return array
	 */
	public function getPreDefinedData()
	{
		//TODO: Write the logic if developer need to tweak the predefined data for email
		return ['email_logo' => "https://flinnt.com/templates/images/email/email-logo-r1.png", 'email_site_name' => "Flinnt.com", 'email_site_url' => "https://flinnt.com", 'email_footer_copyright' => "&#169; " . date("Y") . " flinnt All Rights Reserved.", 'base_href' => "https://flinnt.com", 'email_date_short' => date("Y-m-d"), 'email_date_long' => date("Y-m-d"), 'charset' => "utf-8", 'login_url' => "https://flinnt.com/index.php", 'support_email' => 'test3@synapsesoftech.com', 'support_phone' => '0-79-4014-9800', 'support_phone_tel_url' => '0-79-4014-9800', 'email_footer_facebook' => sprintf('<a href="%s" style="color:#ededed; text-decoration: none; background: none;"><img src="%s" alt="%s" width="48" height="48" border="0" style="display: inline-block;" /></a>', "https://www.facebook.com/Myflinnt", "https://flinnt.com/templates/images/email/facebook.png", "facebook"), 'email_footer_twitter' => sprintf('<a href="%s" style="color:#ededed; text-decoration: none; background: none;"><img src="%s" alt="%s" width="48" height="48" border="0" style="display: inline-block;" /></a>', "http://twitter.com/Flinnts", "https://flinnt.com/templates/images/email/twitter.png", "twitter"), 'email_footer_blogger' => sprintf('<a href="%s" style="color:#ededed; text-decoration: none; background: none;"><img src="%s" alt="%s" width="48" height="48" border="0" style="display: inline-block;" /></a>', "http://blog.flinnt.com", "https://flinnt.com/templates/images/email/blogger.png", "blogger"), 'email_footer_google_plus' => sprintf('<a href="%s" style="color:#ededed; text-decoration: none; background: none;"><img src="%s" alt="%s" width="48" height="48" border="0" style="display: inline-block;" /></a>', "https://plus.google.com/+Flinnt/", "https://flinnt.com/templates/images/email/googleplus.png", "google plus"), 'email_small_icon' => 'https://flinnt.com/templates/images/email/email_small_icon.png', 'contact_us_url' => 'https://flinnt.com/contact', 'extra_info' => '', 'android_app_url' => 'http://www.flinnt.com/google', 'android_app_image' => 'https://flinnt.com/templates/images/android-app-footer.png', 'iphone_app_url' => 'http://www.flinnt.com/apple', 'iphone_app_image' => 'https://flinnt.com/templates/images/ios-app-footer.png'];
	}

	/**
	 * /**
	 * Create the database record.
	 *
	 * @param int      $delay Delay in seconds
	 * @param Mailable $data  Email object data.
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function buildDabaseRecord( $delay, $data )
	{
		if ( $data instanceof Mailable ) {
			$this->validateEnvelope($data);

			$scheduleDate = 0;

			if ( $delay > 0 ) {
				$scheduleDate = $this->getAvailableAt($delay);
			}

			if ( empty($data->from) ) {
				$data->from = config('mail.from');
			}

			$ccAddress = null;
			if ( ! empty($data->cc) ) {
				$ccAddress = $data->cc[0]['address'];
			}

			return ['to_email' => $data->to[0]['address'], 'to_name' => $data->to[0]['name'], 'from_email' => $data->from['address'], 'from_name' => $data->from['name'], 'email_subject' => $data->subject, 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => $scheduleDate, 'cc_email' => $ccAddress, 'send_method' => $data->queue, 'user_id' => \Request::get('user_id', 0),];
		}

		throw new \InvalidArgumentException("Data must be extended from Mailable class.");
	}

	/**
	 * Validate the data.
	 *
	 * @param $data
	 *
	 * @throws \Flinnt\Core\Queue\Mail\Exceptions\InvalidMailException
	 */
	private function validateEnvelope( $data )
	{
		if ( empty($data->subject) || trim($data->subject) == '' ) {
			throw new InvalidMailException("Please provide subject.");
		}

		if ( empty($data->to[0]['address']) || trim($data->to[0]['address']) == '' ) {
			throw new InvalidMailException("Please provide valid recipient address");
		}
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
	 * Deliver the queued message after the given delay.
	 *
	 * @param  \DateTime|int $delay
	 * @param  Queue         $queue
	 *
	 * @return mixed
	 */
	public function later( $delay, Queue $queue )
	{
		$connection = property_exists($this, 'connection') ? $this->connection : null;
		$this->build();
		$pushedData = $queue->connection($connection)->later($delay, $this->createView($this->buildView(), $this->buildViewData()), $this->buildDabaseRecord($delay, $this));
		// reset recipient list to an empty array
		$this->to = [];

		return $pushedData;
	}

	/**
	 * Send email immediately based on parameters specified.
	 *
	 * @param array $recipients Associative array of email address and name
	 *
	 */
	public final function sendNow( array $recipients = null )
	{
		$this->processEmail($this->parseRecipients($recipients));
	}

	/**
	 * Process the email based no parameters.
	 *
	 * @param array|null $recipients
	 * @param bool       $saveInQueue
	 * @param int        $delay
	 *
	 * @return mixed
	 */
	abstract protected function processEmail( array $recipients = null, $saveInQueue = false, $delay = 0 );

	/**
	 * Parse the recipients
	 *
	 * @param $recipients
	 *
	 * @return array
	 */
	public function parseRecipients( $recipients )
	{
		if ( array_key_exists('email', $recipients) ) {
			return array($recipients);
		}

		return $recipients;
	}

	/**
	 * Prepare email based on parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of email address and name
	 *
	 */
	public final function saveInQueue( array $recipients = null )
	{
		$this->processEmail($this->parseRecipients($recipients), true);
	}

	/**
	 * Prepare email based on delay and parameters specified and save to queue.
	 *
	 * @param array $recipients Associative array of email address and name
	 * @param int   $delay      Delay in seconds
	 *
	 */
	public function sendLater( array $recipients = null, $delay )
	{
		$this->processEmail($recipients, false, $delay);
	}

	/**
	 *
	 * Get the parameter used to prepare email content.
	 *
	 * @return array
	 */
	public final function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * Set the parameter to prepare email content.
	 *
	 * @param array $parameters
	 *
	 * @return self
	 */
	public final function setParameters( array $parameters )
	{
		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * Returns the route
	 *
	 * @return string
	 */
	public final function getMailRoute()
	{
		return $this->mailRoute;
	}

	/**
	 * Send or queue email based on parameter passed.
	 *
	 * @param array $recipients
	 * @param bool  $saveInQueue
	 * @param int   $delay
	 */
	protected function sendEmail( array $recipients, $saveInQueue = false, $delay = 0 )
	{
		$recipients = $this->parseRecipients($recipients);
		foreach ( $recipients as $recipient ) {

			if ( $saveInQueue ) {
				if ( array_key_exists('name', $recipient) ) {
					$mail = call_user_func(array($this->mailRoute, 'to'), [["email" => $recipient['email'], "name" => $recipient['name']]]);
					$mail->queue($this);
				}
				else {
					$mail = call_user_func(array($this->mailRoute, 'to'), $recipient['email']);
					$mail->queue($this);
				}
			}
			else {
				if ( ! empty($delay) && $delay != 0 ) {
					if ( array_key_exists('name', $recipient) ) {
						$mail = call_user_func(array($this->mailRoute, 'to'), [["email" => $recipient['email'], "name" => $recipient['name']]]);
						$mail->later($delay, $this);
					}
					else {
						$mail = call_user_func(array($this->mailRoute, 'to'), $recipient['email']);
						$mail->later($delay, $this);
					}
				}
				else {
					if ( array_key_exists('name', $recipient) ) {
						call_user_func(array($this->mailRoute, 'to'), [["email" => $recipient['email'], "name" => $recipient['name']]])->send($this);
					}
					else {
						call_user_func(array($this->mailRoute, 'to'), $recipient['email'])->send($this);
					}

				}
			}

		}

	}

}