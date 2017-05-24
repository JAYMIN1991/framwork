<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 2:52 PM
 */

namespace Flinnt\Core\Queue\SMS;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;

/**
 * Class SMSQueue
 *
 * @package Flinnt\Core\Queue\SMS
 */
class SMSQueue extends AbstractSMSQueue implements QueueContract
{

	/**
	 * The database connection instance.
	 *
	 * @var \Illuminate\Database\Connection
	 */
	protected $database;

	/**
	 * The database table that holds the jobs.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The name of the default queue.
	 *
	 * @var string
	 */
	protected $queue;

	/**
	 * The expiration time of a job.
	 *
	 * @var int|null
	 */
	protected $expire;

	/**
	 * SMSQueue constructor.
	 *
	 * @param \Illuminate\Database\Connection|ConnectionInterface $database
	 * @param string                                              $table
	 * @param string                                              $queue
	 * @param int|null                                            $expire
	 */
	public function __construct( Connection $database, $table, $queue, $expire )
	{
		$this->database = $database;
		$this->table = $table;
		$this->queue = $queue;
		$this->expire = $expire;
	}

	/**
	 * Get the size of the queue.
	 *
	 * @param  string $queue
	 *
	 * @return int
	 */
	public function size( $queue = null )
	{
		return $this->database->table($this->table)->count();
	}

	/**
	 * Push a new job onto the queue.
	 *
	 * @param  string      $job
	 * @param array|string $data
	 * @param  string      $queue
	 *
	 * @return mixed
	 */
	public function push( $job, $data = "", $queue = null )
	{
		return $this->pushToDatabase($this->createPayload($job, $data));
	}

	/**
	 * Insert the record to database.
	 *
	 * @param array $attributes
	 *
	 * @return int
	 */
	protected function pushToDatabase( array $attributes )
	{
		return $this->database->table($this->table)->insertGetId($attributes);
	}

	/**
	 * Push a raw payload onto the queue.
	 *
	 * @param  string $payload
	 * @param  string $queue
	 * @param  array  $options
	 *
	 * @return mixed
	 */
	public function pushRaw( $payload, $queue = null, array $options = [] )
	{
		// TODO: Implement pushRaw() method.
		return null;
	}

	/**
	 * Push a new job onto the queue after a delay.
	 *
	 * @param  \DateTime|int $delay
	 * @param  string        $job
	 * @param array|string   $data
	 * @param  string        $queue
	 *
	 * @return mixed
	 * @deprecated use push method instead.
	 */
	public function later( $delay, $job, $data = "", $queue = null )
	{
		return $this->pushToDatabase($this->createPayload($job, $data));
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string $queue
	 *
	 * @return \Illuminate\Contracts\Queue\Job|null
	 */
	public function pop( $queue = null )
	{
		// TODO: Implement pop() method.
		return null;
	}

}