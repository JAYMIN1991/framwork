<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 13/12/16
 * Time: 2:36 PM
 */

namespace Flinnt\Test\Core\Queue\SMS;


use Mockery as m;
use Flinnt\Core\Queue\SMS\SMSQueue;
use Flinnt\Core\Test\TestCase;

/**
 * Class SMSQueueTest
 *
 * @package Flinnt\Test\Core\Queue\SMS
 */
class SMSQueueTest extends TestCase
{

	/**
	 * Test the push method of sms queue properly push the data to database.
	 *
	 * @return  void
	 */
	public function testPushSMSProperlyPushSMSOntoDatabase()
	{

		/* @var SMSQueue $queue */
		$queue = $this->getMockBuilder('Flinnt\Core\Queue\SMS\SMSQueue')->setMethods(['getTime'])->setConstructorArgs([$database = m::mock('Illuminate\Database\Connection'), TABLE_SMS_QUEUE, 'default', 60])->getMock();
		$queue->expects($this->any())->method('getTime')->will($this->returnValue('time'));

		/* @var Connection $database */
		$database->shouldReceive('table')->with(TABLE_SMS_QUEUE)->andReturn($query = m::mock('StdClass'));

		$query->shouldReceive('insertGetId')->once()->andReturnUsing(function ( $array ) {
			$this->assertEquals('default', $array['sms_mode']);
			$this->assertEquals('Test SMS Template', $array['message_text']);
			$this->assertEquals(0, $array['user_id']);
			$this->assertEquals('1234567890', $array['mobile_no']);
		});

		$data = ['mobile_no' => '1234567890', 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => time(), 'sms_mode' => 'default', 'user_id' => 0,];

		$queue->push("Test SMS Template", $data);
	}
}