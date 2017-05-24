<?php
	namespace Mobisoft\SMS\API\Contracts;


	/**
	 * Class SMS_XML_API
	 *
	 * @package Mobisoft\SMS\API\Contracts
	 */
	abstract class SMS_XML_API
	{
		var $messages;
		var $user;
		var $password;
		var $gsmid;
		var $url;
		var $headers;
		var $errors;
		var $last_payload;
		var $last_result;
		var $last_error;
		var $last_error_code;


		/**
		 * SMS_XML_API constructor.
		 */
		public function __construct() {
			$this->payload = NULL;
		}

		/**
		 * @param $user_name
		 * @param $password
		 * @param $gsm_id
		 * @param string $URL
		 */
		public function setParameters($user_name, $password, $gsm_id, $URL="") {
			$this->user = $user_name;
			$this->password = $password;
			$this->gsmid = $gsm_id;
			$this->errors = [];
			$this->headers = NULL;
			$this->last_payload = NULL;

			if(!empty($URL)) {
				$this->url = $URL;
			}
		}

		/**
		 * @param $user_name
		 * @return $this
		 */
		public function setUserName($user_name) {
			$this->user = $user_name;

			return $this;
		}

		public function getUserName() {
			return $this->user;
		}

		/**
		 * @param $pwd_plain
		 * @return $this
		 */
		public function setPassword($pwd_plain) {
			$this->password = $pwd_plain;

			return $this;
		}

		public function getPassword() {
			return $this->password;
		}

		/**
		 * @param $gsm_id
		 * @return $this
		 */
		public function  setGSMID($gsm_id) {
			$this->gsmid = $gsm_id;

			return $this;
		}

		public function getGSMID() {
			return $this->gsmid;
		}

		/**
		 * @param $URL
		 * @return $this
		 */
		public function setURL($URL) {
			$this->url = $URL;

			return $this;
		}

		public function  getURL() {
			return $this->url;
		}

		/**
		 * @return int
		 */
		public function getMessageCount() {
			return empty($this->messages) ? 0 : count($this->messages);
		}

		public function getPayload() {
			return $this->last_payload;
		}

		/**
		 * @param array $custom_headers
		 * @return $this
		 */
		public function setHeaders(array $custom_headers) {
			$this->headers = $custom_headers;

			return $this;
		}

		public function getHeaders() {
			return $this->headers;
		}

		public function Clear() {
			$this->errors = [];
			$this->messages = [];
		}

		public function getErrors() {
			return $this->errors;
		}

		/**
		 * @param $mobile
		 * @param $message
		 * @param $ref_id
		 */
		public function addMessage($mobile, $message, $ref_id) {
			$this->messages[$message][] = array($mobile, $ref_id);
		}

		/**
		 * @return array
		 */
		public function getLastResult() {
			return [
					"result" => $this->last_result,
					"error" => "" . $this->last_error_code . ": " . $this->last_error
					];
		}

		/**
		 * @param string $verb
		 * @return mixed
		 */
		abstract public function Send($verb="POST");
		abstract protected function generate_payload();

	}