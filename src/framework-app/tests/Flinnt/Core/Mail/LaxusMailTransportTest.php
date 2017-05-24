<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 12/12/16
 * Time: 5:56 PM
 */

namespace Flinnt\Test\Core\Mail;


use Flinnt\Core\Mail\LaxusMailTransportManager;
use Flinnt\Core\Test\TestCase;
use Illuminate\Support\Collection;

/**
 * Class LaxusMailTransportTest
 *
 * @package Flinnt\Test\Core\Mail
 */
class LaxusMailTransportTest extends TestCase
{

	/**
	 * Test laxusmail transport is generated properly
	 *
	 * @return  void
	 */
	public function testGetLaxusMailTransport()
	{
		$app = ['config' => new Collection(['services.gmail' => ['host' => 'smtp.gmail.com', 'port' => '465', 'username' => 'testbypankit@gmail.com', 'password' => 'pankit123456', 'encryption' => 'SSL', 'from' => ['address' => 'no-reply@test.com', 'name' => 'flinnt'],],]),];

		$manager = new LaxusMailTransportManager($app);

		/* @var \Swift_SmtpTransport $laxusmail */
		$transport = $manager->driver('gmail');

		$this->assertEquals('smtp.gmail.com', $transport->getHost());
	}
}