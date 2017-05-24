<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/11/16
 * Time: 11:26 AM
 */

namespace Flinnt\Core\Queue\Mail\Connectors;


use Flinnt\Core\Mail\EmailQueue;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Arr;

/**
 * Class EmailConnector
 *
 * @package App\Common\CustomQueue\Connectors
 */
class EmailConnector implements ConnectorInterface
{

	/**
	 * @var \Illuminate\Database\ConnectionResolverInterface
	 */
	protected $connections;

	/**
	 * EmailConnector constructor.
	 *
	 * @param \Illuminate\Database\ConnectionResolverInterface $connections
	 */
	public function __construct( ConnectionResolverInterface $connections )
	{
		$this->connections = $connections;
	}

	/**
	 * Establish a queue connection.
	 *
	 * @param  array $config
	 *
	 * @return \Illuminate\Contracts\Queue\Queue
	 */
	public function connect( array $config )
	{
		return new EmailQueue($this->connections->connection(Arr::get($config, 'connection')), $config['table'], $config['queue'], Arr::get($config, 'retry_after', 60));
	}
}