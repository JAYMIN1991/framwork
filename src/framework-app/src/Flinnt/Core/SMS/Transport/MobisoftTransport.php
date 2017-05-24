<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/11/16
 * Time: 3:52 PM
 */

namespace Flinnt\Core\SMS\Transport;


use Flinnt\Core\Contracts\SMS\Transport\ITransport;
use Mobisoft\SMS\API\MobiSoft;

/**
 * Class MobisoftTransport
 *
 * @package Flinnt\Core\SMS\Transport
 */
class MobisoftTransport implements ITransport
{

	/**
	 * @var MobiSoft
	 */
	protected $mobisoft;
	/**
	 * @var string username of mobisoft
	 */
	private $username = "";
	/**
	 * @var string password for mobisoft gateway
	 */
	private $password = "";
	/**
	 * @var string Gsm ID for mobisoft gateway
	 */
	private $gsm = "";
	/**
	 * @var string Url of mobisoft gateway
	 */
	private $url = "";

	/**
	 * MobisoftTransport constructor.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $gsm
	 * @param string $url
	 */
	public function __construct( $username, $password, $gsm, $url )
	{
		$this->username = $username;
		$this->password = $password;
		$this->gsm = $gsm;
		$this->url = $url;
		$this->initialize();
	}

	/**
	 * Initialize the MobiSoft API to send SMS.
	 *
	 */
	private function initialize()
	{
		$this->mobisoft = new MobiSoft();
		$headers = [];
		$headers[] = 'Content-Type: text/xml; charset=UTF-8';
		$headers[] = "SOAPAction: " . $this->url;
		$this->mobisoft->setParameters($this->username, $this->password, $this->gsm, $this->url);
		$this->mobisoft->setHeaders($headers);
	}

	/**
	 * /**
	 * Send the sms through specified gateway
	 *
	 * @param string $content
	 * @param string $mobileNo
	 *
	 * @return bool|\SimpleXMLElement
	 */
	public function send( $content, $mobileNo )
	{
		$this->mobisoft->addMessage($mobileNo, $content, 1);

		return $this->mobisoft->send();
	}

	/**
	 * @return bool
	 */
	public function sendToMultiple()
	{
		if ( $this->mobisoft->getMessageCount() > 0 ) {
			return $this->mobisoft->send();
		}

		return false;
	}

	/**
	 * @param $content
	 * @param $mobileNo
	 *
	 * @return mixed|void
	 */
	public function addToMultipleSend( $content, $mobileNo )
	{
		$this->mobisoft->addMessage($content, $mobileNo, $this->mobisoft->getMessageCount() + 1);
	}

	public function resetMultiple()
	{
		$this->mobisoft->Clear();
	}
}