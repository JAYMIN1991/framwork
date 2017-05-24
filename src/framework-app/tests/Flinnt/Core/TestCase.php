<?php

/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/12/16
 * Time: 6:04 PM
 */

namespace Flinnt\Core\Test;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class TestCase
 *
 * @package Flinnt\Core\Test
 */
class TestCase extends BaseTestCase
{

	/**
	 * The base URL to use while testing the application.
	 *
	 * @var string
	 */
	protected $baseUrl = 'http://backoffice.laravel.com:8081';

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__ . '/../../../../../bootstrap/app.php';

		$app->make(Kernel::class)->bootstrap();

		return $app;
	}
}