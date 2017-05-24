<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/12/16
 * Time: 12:53 PM
 */

namespace Flinnt\Test\Core\SMS;


use Flinnt\Core\SMS\SMSNotifiable;
use Flinnt\Core\Test\TestCase;
use Mockery as m;

//use SMSNotifiableMock;

/**
 * Class SMSNotifiableTest
 *
 * @package Flinnt\Test\Core\SMS
 */
class SMSNotifiableTest extends TestCase
{

	/**
	 * Test send method of SMSNotifiable
	 */
	public function testSendMethod()
	{

		$sms = new SMSNotifiableMock();
		$smsHandler = $this->getMockBuilder('Flinnt\Core\SMS\SMSHandler')->setMethods(['send'])->setConstructorArgs([$gateway = m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher')])->getMock();

		$smsHandler->expects($this->once())->method('send')->with("Test Content");
		$sms->to('123456789');
		$sms->send($smsHandler);
	}

	/**
	 * Test queue method of SMSNotifiable
	 */
	public function testQueueMethod()
	{
		$sms = new SMSNotifiableMock();
		$queue = m::mock('\Illuminate\Contracts\Queue\Factory');
		$smsQueue = $this->getMockBuilder('Flinnt\Core\Queue\SMS\SMSQueue')->setMethods(['push'])->setConstructorArgs([$gateway = m::mock('\Illuminate\Database\Connection'), TABLE_SMS_QUEUE, 'default', 60])->getMock();
		$queue->shouldReceive('connection')->once()->with("sms")->andReturn($smsQueue);

		$value = array('mobile_no' => '123456789', 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => 0, 'sms_mode' => 'default', 'user_id' => 0);
		$smsQueue->expects($this->once())->method('push')->with("Test Content", $value);

		$sms->to('123456789');
		$sms->queue($queue);
	}

	/**
	 * Test later method of SMSNotifiable
	 */
	public function testLaterMethod()
	{
		$sms = new SMSNotifiableMock();
		$queue = m::mock('\Illuminate\Contracts\Queue\Factory');
		$smsQueue = $this->getMockBuilder('Flinnt\Core\Queue\SMS\SMSQueue')->setMethods(['push'])->setConstructorArgs([$gateway = m::mock('\Illuminate\Database\Connection'), TABLE_SMS_QUEUE, 'default', 60])->getMock();
		$queue->shouldReceive('connection')->once()->with("sms")->andReturn($smsQueue);

		$value = array('mobile_no' => '123456789', 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => time() + 300, 'sms_mode' => 'default', 'user_id' => 0);
		$smsQueue->expects($this->once())->method('push')->with("Test Content", $value);

		$sms->to('123456789');
		$sms->later(300, $queue);
	}

}

/**
 * Class SMSNotifiableMock
 *
 * @package Flinnt\Test\Core\SMS
 */
class SMSNotifiableMock extends SMSNotifiable
{

	/**
	 * Build the message.
	 *
	 * @return void
	 */
	public function build()
	{
		$this->content = "Test Content";
	}

	/**
	 * Process the sms.
	 *
	 * @param array|null $recipients
	 * @param bool       $saveInQueue
	 * @param int        $delay
	 *
	 */
	public function processSMS( array $recipients = null, $saveInQueue = false, $delay = 0 )
	{
		// TODO: Implement processSMS() method.
	}
}