<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 14/12/16
 * Time: 10:58 AM
 */

namespace Flinnt\Test\Core\SMS;


use Flinnt\Core\SMS\SMSHandler;
use Flinnt\Core\SMS\SMSHandlerBridge;
use Mockery as m;
use Flinnt\Core\Test\TestCase;

/**
 * Class SMSHandlerTest
 *
 * @package Flinnt\Test\Core\SMS
 */
class SMSHandlerTest extends TestCase
{

	/**
	 * Test the send method of SMSHandler when SMS is plain text
	 */
	public function testSendSMSWhenSMSIsPlainText()
	{

		/* @var SMSHandler $smsHandler */
		$smsHandler = $this->getMockBuilder('Flinnt\Core\SMS\SMSHandler')->setMethods(['getGateway'])->setConstructorArgs([$gateway = m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher')])->getMock();
		$smsHandler->expects($this->once())->method('getGateway')->will($this->returnValue($gateway));

		$message = "Test SMS";

		$smsHandler->getGateway()->shouldReceive('send')->once()->with($message, []);

		$smsHandler->send($message, []);
	}

	/**
	 * Test if queue method receives SMSNotifiable as content
	 */
	public function testQueueMethodShouldReceiveSMSNotifibaleAsContent()
	{
		$smsHandler = $this->getMockBuilder('Flinnt\Core\SMS\SMSHandler')->setMethods(['queue'])->setConstructorArgs([$gateway = m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher')])->getMock();

		$smsHandler->expects($this->once())->method('queue')->will($this->returnValue(null));
		$smsHandler->queue('xyz');
	}

	/**
	 * Test if later method receives SMSNotifiable as content
	 */
	public function testLaterMethodShouldReceiveSMSNotifibaleAsContent()
	{
		$smsHandler = $this->getMockBuilder('Flinnt\Core\SMS\SMSHandler')->setMethods(['later'])->setConstructorArgs([$gateway = m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher')])->getMock();

		$smsHandler->expects($this->once())->method('later')->will($this->returnValue(null));
		$smsHandler->later('xyz', 0);
	}

	/**
	 * Test to method of SMSHandler
	 */
	public function testToMethod()
	{
		$smsHandler = new SMSHandler($transport = m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher'));

		$this->assertInstanceOf(SMSHandlerBridge::class, $smsHandler->to('123456789'));
	}

	/**
	 * Test toArray method of SMSHandler
	 */
	public function testToArrayMethod()
	{
		$smsHandler = new SMSHandler(m::mock('Flinnt\Core\SMS\Transport\MobisoftTransport'), m::mock('Illuminate\Events\Dispatcher'));

		$this->assertInstanceOf(SMSHandlerBridge::class, $smsHandler->to(['123456789']));
	}

}