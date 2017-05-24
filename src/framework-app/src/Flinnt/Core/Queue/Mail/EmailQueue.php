<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 5/11/16
 * Time: 12:57 PM
 */

namespace Flinnt\Core\Mail;

use Flinnt\Core\Queue\Mail\AbstractEmailQueue;
use Illuminate\Database\Connection;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Database\ConnectionInterface;

/**
 * Class EmailQueue
 *
 * @package Flinnt\Core\Mail
 */
class EmailQueue extends AbstractEmailQueue implements QueueContract
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
	 * EmailQueue constructor.
	 *
	 * @param \Illuminate\Database\Connection|ConnectionInterface $database
	 * @param                                                     $table
	 * @param string                                              $default
	 * @param int                                                 $expire
	 */
	public function __construct( Connection $database, $table, $default = "default", $expire = 60 )
	{
		$this->database = $database;
		$this->table = $table;
		$this->queue = $default;
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
	 * @param  string $job
	 * @param  mixed  $data
	 * @param  string $queue
	 *
	 * @return mixed
	 */
	public function push( $job, $data = "", $queue = null )
	{
		return $this->pushToDatabase($this->createPayload($job, $data));
	}

	/**
	 * Store the data to database
	 *
	 * @param $attributes
	 *
	 * @return int
	 */
	private function pushToDatabase( $attributes )
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
	 * @param  mixed         $data
	 * @param  string        $queue
	 *
	 * @return mixed
	 *
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

	/**
	 * Push a new job onto the queue.
	 *
	 * @param  string $queue
	 * @param  string $job
	 * @param  mixed  $data
	 *
	 * @return mixed
	 */
	public function pushOn( $queue, $job, $data = '' )
	{
		// TODO: Implement pushOn() method.
		return null;
	}

	/**
	 * Push a new job onto the queue after a delay.
	 *
	 * @param  string        $queue
	 * @param  \DateTime|int $delay
	 * @param  string        $job
	 * @param  mixed         $data
	 *
	 * @return mixed
	 */
	public function laterOn( $queue, $delay, $job, $data = '' )
	{
		// TODO: Implement laterOn() method.
		return null;
	}
}