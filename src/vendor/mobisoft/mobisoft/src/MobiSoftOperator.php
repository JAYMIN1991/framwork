<?php
	namespace Mobisoft\SMS\API;

	use Mobisoft\SMS\API\Contracts\SMS_XML_API;

	/*
	 * Request Example
	 *
	 * <MESSAGE VER="1.2">
	 * <USER USERNAME="xxx" PASSWORD="xxx" DLR="0"/>
	 * <SMS TEXT="This is first sms" ID="1">
	 *      <ADDRESS FROM="MBSOFT" TO="919909903667" SEQ="1"/>
	 *      <ADDRESS FROM="MBSOFT" TO="919879053667" SEQ="2"/>
	 * </SMS>
	 * <SMS TEXT="This is second sms" ID="2">
     *      <ADDRESS FROM="MBSOFT" TO="919909903667" SEQ="1"/>
	 *      <ADDRESS FROM="MBSOFT" TO="919879053667" SEQ="2"/>
	 * </SMS>
	 * </MESSAGE>
	 */

	/*
	 * Acknowledgement Example
	 *
	 * <? xml version='1.0' encoding='ISO-8859-1'?>
	 * <MESSAGEACK>
	 *      <SUCCESS ID="1" MID="YPjgwx7s50i6xx14rqwm5n8f" SEQ="1"/>
	 *      <SUCCESS ID="1" MID="Yugnfiega63r42xw60gb9gbb" SEQ="2"/>
	 *      <SUCCESS ID="2" MID="Yl40w9w7ndmdt8sbhxea4ga3" SEQ="1"/>
	 *      <ERROR ID="2" CODE="0x200" SEQ="2"/>
	 * </MESSAGEACK>
	 *
	 */

	/**
	 * Class MobiSoftOperator
	 *
	 * @package Mobisoft\SMS\API
	 */
	class MobiSoftOperator extends SMS_XML_API
	{
		/**
		 * @param string $verb
		 * @return bool|\SimpleXMLElement
		 */
		public function Send($verb="POST") {
			$this->last_result = null;
			$this->last_payload = null;

			if( strtolower( gettype( $payload = $this->generate_payload() ) ) == "boolean" ) {
				$this->last_payload = $payload;
				return false;
			}
			$this->last_payload = $payload;

			$url = $this->getURL();
			$process = curl_init($this->url);
			if(!empty($this->headers) && is_array($this->headers)) {
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
			}
			curl_setopt($process, CURLOPT_HEADER, 0);
			curl_setopt($process, CURLOPT_TIMEOUT, 30);
			if(strtolower($verb) == "post") {
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_POSTFIELDS, "action=send&data=" . $payload);
			}
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($process, CURLOPT_ENCODING, "utf-8");
			$return = curl_exec($process);
			curl_close($process);

			$this->last_result = $return;
			$this->last_error = curl_error($process);
			$this->last_error = curl_errno($process);

			if ($return === false) {
				return false;
			}

			$out = simplexml_load_string($return);
			if ($out === null) {
				return false;
			}

			return $out;
		}

		/**
		 * @return bool|string
		 */
		protected function generate_payload() {
			$xml_base = '<MESSAGE VER="1.2"></MESSAGE>';
			$options = LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR;
			if(defined('LIBXML_COMPACT')) {
				$options = $options | LIBXML_COMPACT;
			}

			try {
				$payload = new \SimpleXMLElement($xml_base, $options);

				$auth = $payload->addChild("USER");
				$auth->addAttribute("USERNAME", $this->user);
				$auth->addAttribute("PASSWORD", $this->password);
				$auth->addAttribute("DLR", 0);

				foreach($this->messages as $message=>$message_data) {

					foreach($message_data as $data) {
						list($mobile_nos, $ref_id) = $data;

						if(is_array($mobile_nos)) {
							foreach($mobile_nos as $mobile_no) {
								$sms = $payload->addChild("SMS");
								$sms->addAttribute("TEXT", $message);
								$sms->addAttribute("ID", $ref_id);

								$address = $sms->addChild("ADDRESS");
								$address->addAttribute("FROM", $this->gsmid);
								$address->addAttribute("TO", $mobile_no);
								$address->addAttribute("SEQ", $ref_id);
							}
						} else {
							$sms = $payload->addChild("SMS");
							$sms->addAttribute("TEXT", $message);
							$sms->addAttribute("ID", $ref_id);

							$address = $sms->addChild("ADDRESS");
							$address->addAttribute("FROM", $this->gsmid);
							$address->addAttribute("TO", $mobile_nos);
							$address->addAttribute("SEQ", $ref_id);
						}

					}
				}
			} catch(\Exception $e) {
				$this->errors[] = $e;
				return false;
			}

			//$xml = $payload->asXML();
			$dom = dom_import_simplexml($payload);
			return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
		}

	}