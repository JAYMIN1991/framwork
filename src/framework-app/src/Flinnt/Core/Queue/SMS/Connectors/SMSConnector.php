<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 2:49 PM
 */

namespace Flinnt\Core\Queue\SMS\Connectors;


use Flinnt\Core\Queue\SMS\SMSQueue;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Arr;

/**
 * Class SMSConnector
 *
 * @package Flinnt\Core\Queue\SMS\Connectors
 */
class SMSConnector implements ConnectorInterface
{

	/**
	 * @var ConnectionResolverInterface
	 */
	protected $connection;

	/**
	 * SMSConnector constructor.
	 *
	 * @param \Illuminate\Database\ConnectionResolverInterface $connection
	 */
	public function __construct( ConnectionResolverInterface $connection )
	{
		$this->connection = $connection;
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
		return new SMSQueue($this->connection->connection(Arr::get($config, 'connection')), $config['table'], $config['queue'], Arr::get($config, 'retry_after', 60));
	}


}