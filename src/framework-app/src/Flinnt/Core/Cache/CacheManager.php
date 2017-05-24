<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 21/11/16
 * Time: 2:31 PM
 */

namespace Flinnt\Core\Cache;

use Cache;
use DB;

/**
 * Class CacheManager
 *
 * @package Flinnt\Core\Cache
 */
class CacheManager
{

	/**
	 *
	 */
	CONST TAG_VIEW = "dftgview";

	/**
	 *
	 */
	CONST TAG_RAW_DATA = "dftgrawdata";

	/**
	 *
	 */
	CONST TAG_QUERY = "dftgquery";

	/**
	 * Store the html view in redis database.
	 *
	 * @param String            $view HTML view
	 * @param String            $key  redis key
	 * @param int|null          $time time in seconds
	 * @param String|array|null $tags tags if applicable
	 *
	 * @return String|bool if view stored successfully than return view else return false
	 */
	public function view( $view, $key, $time = null, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_VIEW);

		return $this->storeCache($view, $key, $time, $finalTags);
	}

	/**
	 * @param String|array|null $tags
	 * @param string            $default
	 *
	 * @return array
	 */
	private function parseTags( $tags = null, $default )
	{
		$tagArray = array($default);

		if ( ! empty($tags) ) {
			if ( is_string($tags) ) {
				$tagArray[] = $tags;
			}
			else {
				$tagArray = array_merge($tagArray, $tags);
			}
		}

		return $tagArray;
	}

	/**
	 * Store the content to redis database.
	 *
	 * @param String|array $data
	 * @param String       $key
	 * @param String|null  $time
	 * @param String|array $tags
	 *
	 * @return mixed
	 */
	private function storeCache( $data, $key, $time, $tags )
	{

		$minutes = $time / 60;

		if ( empty($time) ) {
			return Cache::tags($tags)->rememberForever($key, function () use ( $data ) {
				return $data;
			});
		}
		else {
			return Cache::tags($tags)->remember($key, $minutes, function () use ( $data ) {
				return $data;
			});
		}
	}

	/**
	 * Return html view if exist in cache, else returns false.
	 *
	 * @param String            $key
	 * @param String|array|null $tags
	 *
	 * @return String|bool
	 */
	public function getView( $key, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_VIEW);

		return $this->getData($key, $finalTags);
	}

	/**
	 * Returns the data based on key and tags
	 *
	 * @param String            $key
	 * @param String|array|null $tags
	 *
	 * @return String|null
	 */
	private function getData( $key, $tags )
	{
		if ( empty($tags) ) {
			if ( $this->exists($key) ) {
				return Cache::get($key);
			}
		}
		else {
			if ( $this->exists($key, $tags) ) {
				return Cache::tags($tags)->get($key);
			}
		}

		return null;
	}

	/**
	 * Check if key is exists in redis database.
	 *
	 * @param String            $key  redis key
	 * @param String|array|null $tags array of tags if applicable
	 *
	 * @return bool return true if exists otherwise false
	 */
	private function exists( $key, $tags = null )
	{
		if ( ! empty($tags) ) {
			return Cache::tags($tags)->has($key);
		}
		else {
			return Cache::has($key);
		}
	}

	/**
	 * Remove the view from cache
	 *
	 * @param String            $key
	 * @param String|array|null $tags
	 *
	 * @return bool
	 */
	public function forgetView( $key, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_VIEW);

		return $this->forget($key, $finalTags);
	}

	/**
	 * Remove the key from redis database
	 *
	 * @param string            $key  redis key
	 * @param String|array|null $tags tags if applicable
	 *
	 * @return bool true if deleted otherwise false
	 */
	private function forget( $key, $tags = null )
	{
		if ( empty($tags) ) {
			if ( $this->exists($key) ) {
				return Cache::forget($key);
			}
		}
		else {
			if ( $this->exists($key, $tags) ) {
				return Cache::tags($tags)->forget($key);
			}
		}

		return false;
	}

	/**
	 * Store the raw data in redis.
	 *
	 * @param mixed             $data raw data
	 * @param String            $key  redis key
	 * @param int|null          $time time in seconds
	 * @param String|array|null $tags tags if applicable
	 *
	 * @return mixed if data stored successfully than return data else return false
	 */
	public function rawData( $data, $key, $time = null, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_RAW_DATA);

		return $this->storeCache($data, $key, $time, $finalTags);
	}

	/**
	 * Return the raw data
	 *
	 * @param String            $key
	 * @param String|array|null $tags
	 *
	 * @return String|bool
	 */
	public function getRawData( $key, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_RAW_DATA);

		return $this->getData($key, $finalTags);
	}

	/**
	 * Remove the raw data from cache
	 *
	 * @param String            $key
	 * @param String|array|null $tags
	 *
	 * @return bool
	 */
	public function forgetRawData( $key, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_RAW_DATA);

		return $this->forget($key, $finalTags);
	}

	/**
	 * Store the data of query in redis cache.
	 *
	 * @param String            $query    sql query
	 * @param array|null        $bindings array of sql bindings
	 * @param String|null       $time     time in seconds
	 * @param String|array|null $tags     tags if applicable
	 *
	 * @return mixed
	 */
	public function query( $query, array $bindings = [], $time = null, $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_QUERY);

		$minutes = $time / 60;

		if ( empty($time) ) {
			return Cache::tags($finalTags)->rememberForever(md5($query . serialize($bindings)), function () use ( $query, $bindings ) {
				return DB::select(DB::raw($query), $bindings);
			});
		}
		else {
			return Cache::tags($finalTags)->remember(md5($query . serialize($bindings)), $minutes, function () use ( $query, $bindings ) {
				return DB::select(DB::raw($query), $bindings);
			});
		}

	}

	/**
	 * Return the result of query if exists in database otherwise false.
	 *
	 * @param String            $query    Sql query
	 * @param array|null        $bindings bindings of query
	 * @param String|array|null $tags     array of tags if applicable
	 *
	 * @return String|null returns string if query is available otherwise false.
	 */
	public function getQuery( $query, array $bindings = [], $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_QUERY);
		$sql = $query;
		if ( ! empty($bindings) ) {
			$sql = $query . serialize($bindings);
		}
		$md5Sql = md5($sql);

		if ( $this->exists($md5Sql, $finalTags) ) {
			return Cache::tags($finalTags)->get($md5Sql);
		}

		return null;
	}

	/**
	 * Remove the result of cached query.
	 *
	 * @param String            $query
	 * @param array             $bindings
	 * @param String|array|null $tags
	 *
	 * @return bool
	 */
	public function forgetQuery( $query, array $bindings = [], $tags = null )
	{
		$finalTags = $this->parseTags($tags, self::TAG_QUERY);
		$sql = $query;
		if ( ! empty($bindings) ) {
			$sql = $query . serialize($bindings);
		}

		$md5Sql = md5($sql);

		if ( $this->exists($md5Sql, $finalTags) ) {
			return Cache::tags($finalTags)->forget($md5Sql);
		}

		return false;
	}

	/**
	 * Remove keys of provided tags.
	 *
	 * @param String|array $tags array of tags
	 *
	 * @return void
	 */
	public function flush( $tags )
	{
		Cache::tags($tags)->flush();
	}
}