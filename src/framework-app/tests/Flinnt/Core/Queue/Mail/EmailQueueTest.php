<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 13/12/16
 * Time: 11:35 AM
 */

namespace Flinnt\Test\Core\Queue\Mail;

use Flinnt\Core\Mail\EmailQueue;
use Illuminate\Database\Connection;
use Mockery as m;
use Flinnt\Core\Test\TestCase;

/**
 * Class EmailQueueTest
 *
 * @package Flinnt\Test\Core\Queue\Mail
 */
class EmailQueueTest extends TestCase
{

	/**
	 * Test the push method of email queue properly push the data to database.
	 *
	 * @return  void
	 */
	public function testPushProperlyPushEmailOntoDatabase()
	{

		/* @var EmailQueue $queue */
		$queue = $this->getMockBuilder('Flinnt\Core\Mail\EmailQueue')->setMethods(['getTime'])->setConstructorArgs([$database = m::mock('Illuminate\Database\Connection'), TABLE_EMAIL_QUEUE, 'default'])->getMock();
		$queue->expects($this->any())->method('getTime')->will($this->returnValue('time'));

		/* @var Connection $database */
		$database->shouldReceive('table')->with(TABLE_EMAIL_QUEUE)->andReturn($query = m::mock('StdClass'));

		$query->shouldReceive('insertGetId')->once()->andReturnUsing(function ( $array ) {
			$this->assertEquals('default', $array['send_method']);
			$this->assertEquals('Test Template', $array['email_body_html']);
			$this->assertEquals(0, $array['user_id']);
			$this->assertEquals('example@email.com', $array['to_email']);
		});

		$data = ['to_email' => 'example@email.com', 'to_name' => 'Example', 'from_email' => 'myself@email.com', 'from_name' => 'Myself', 'email_subject' => 'Unit Test Mail', 'queue_status' => 0, 'queue_date' => time(), 'schedule_dt' => time(), 'cc_email' => 'cc@email.com', 'send_method' => 'default', 'user_id' => 0,];
		$queue->push('Test Template', $data);
	}
}