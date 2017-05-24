<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/12/16
 * Time: 6:23 PM
 */

namespace Flinnt\Repository\Test;


use Illuminate\Contracts\Console\Kernel;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

	/**
	 * Creates the application.
	 *
	 * Needs to be implemented by subclasses.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../../../../../../bootstrap/app.php';

		$app->make(Kernel::class)->bootstrap();

		return $app;
	}
}