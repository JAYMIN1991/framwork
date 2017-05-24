<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 9/2/17
 * Time: 12:19 PM
 */

namespace Flinnt\Repository\Exceptions;


use Flinnt\Core\Exceptions\BaseException;

/**
 * Class RecordNotFoundException
 * @package Flinnt\Repository\Exceptions
 */
class RecordNotFoundException extends BaseException {

	/**
	 * Name of the affected table.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The affected table IDs.
	 *
	 * @var int|array
	 */
	protected $ids;

	/**
	 * RecordNotFoundException constructor.
	 *
	 * @param string    $table
	 * @param array|int $ids
	 */
	public function __construct( $table, $ids = [] ) {
		$this->table = $table;
		$this->ids = is_array($ids) ? $ids : [$ids];

		if ( count($this->ids) > 0 ) {
			$message = trans('exception.no_query_result.message',
				['table' => $this->table, 'keys' => implode(', ', $this->ids)]);
		} else {
			$message = trans('exception.no_query_result.message', ['table' => $this->table, 'keys' => '']);
		}

		parent::__construct(trans('exception.no_query_result.code'), $message,
			trans('exception.something_wrong.message'));
	}
}