<?php

namespace Flinnt\Core\Console\Mail;

use Flinnt\Core\Contracts\Mail\Mailable;

class ShutDownHandlerMail extends Mailable
{
	protected $mailViewData = [];

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('mail.shutdown-handler-mail', $this->mailViewData);
	}

	protected function processEmail(array $recipients = null, $saveInQueue = false, $delay = 0)
	{
		$this->mailViewData = $this->parameters;

		$this->sendEmail($recipients);
	}
}