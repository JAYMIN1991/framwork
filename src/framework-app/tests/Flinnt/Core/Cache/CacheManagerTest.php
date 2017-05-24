<?php

/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 9/12/16
 * Time: 2:10 PM
 */
namespace Flinnt\Core\Test\Core\Cache;

use Mockery as m;
use Flinnt\Core\Cache\CacheManager;
use Flinnt\Core\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class CacheManagerTest
 *
 * @package Flinnt\Core\Test\Core\Cache
 */
class CacheManagerTest extends TestCase
{

	use DatabaseTransactions;

	/**
	 * Test if view without tags can be store in cache
	 *
	 * @return void
	 */
	public function testViewCanBeStoredInCacheWithoutTags()
	{

		$cache = new CacheManager;
		$template = "Test Template";
		$key = __METHOD__ . "TestKey";

		$cache->view($template, $key);
		$storedTemplate = $cache->getView($key);

		$this->assertEquals($template, $storedTemplate);
	}

	/**
	 * Test if view with tags can be stored in cache
	 *
	 * @return void
	 */
	public function testViewCanBeStoredInCacheWithTags()
	{

		$cache = new CacheManager;
		$template = "Test Template";
		$key = __METHOD__ . "::TestKeyTags";
		$tags = "test";

		$cache->view($template, $key, null, $tags);
		$this->assertEquals($template, $cache->getView($key, $tags));
	}

	/**
	 *  Test if view without tags can be removed
	 *
	 * @return  void
	 */
	public function testViewWithoutTagsCanBeRemoved()
	{
		$cache = new CacheManager;
		$template = "Test Template";
		$key = __METHOD__ . "TestKey";

		$cache->view($template, $key);
		$storedTemplate = $cache->getView($key);

		$this->assertEquals($template, $storedTemplate);
	}

	/**
	 * Test if view with tags can be removed
	 *
	 * @return void
	 */
	public function testViewWithTagsCanBeRemoved()
	{
		$cache = new CacheManager;
		$template = "Test Template";
		$key = __METHOD__ . "TestKey";
		$tag = "test";
		$cache->view($template, $key, null, $tag);
		$cache->forgetView($key, $tag);

		$this->assertNull($cache->getView($key, $tag));
	}

	/**
	 * Test if raw data without tags can be stored
	 *
	 * @return void
	 */
	public function testRawDataCanBeStoredInCacheWithoutTags()
	{
		$cache = new CacheManager;
		$template = array("test1" => "Test1", "test2" => "Test2", "test3" => "Test3");

		$key = __METHOD__ . "TestKey";

		$cache->rawData($template, $key);
		$storedTemplate = $cache->getRawData($key);

		$this->assertEquals($template, $storedTemplate);
	}

	/**
	 * Test if raw data with tags can be stored
	 *
	 * @return void
	 */
	public function testRawDataCanBeStoredInCacheWithTags()
	{
		$cache = new CacheManager;
		$template = array("test1" => "Test1", "test2" => "Test2", "test3" => "Test3");
		$tag = "testRawData";
		$key = __METHOD__ . "TestKey";

		$cache->rawData($template, $key, null, $tag);
		$storedTemplate = $cache->getRawData($key, $tag);

		$this->assertEquals($template, $storedTemplate);
	}

	/**
	 * Test if raw data with tags can be removed
	 *
	 * @return  void
	 */
	public function testRawDataWithTagCanBeRemoved()
	{
		$cache = new CacheManager;
		$template = array("test1" => "Test1", "test2" => "Test2", "test3" => "Test3");
		$tag = "testRawData";
		$key = __METHOD__ . "TestKey";

		$cache->rawData($template, $key, null, $tag);
		$cache->forgetRawData($key, $tag);

		$this->assertNull($cache->getRawData($key, $tag));
	}

	/**
	 * Test if raw data without tags can be removed
	 *
	 * @return void
	 */
	public function testRawDataWithoutTagCanBeRemoved()
	{
		$cache = new CacheManager;
		$template = array("test1" => "Test1", "test2" => "Test2", "test3" => "Test3");
		$key = __METHOD__ . "TestKey";

		$cache->rawData($template, $key);
		$cache->forgetRawData($key);

		$this->assertNull($cache->getRawData($key));
	}

	/**
	 * Test if query without tags can be stored
	 *
	 * @return void
	 */
	public function testQueryCanBeStoredInCacheWithoutTags()
	{
		$cache = new CacheManager;
		$query = 'SELECT * FROM ' . TABLE_ADMIN_USERS . ' WHERE "user_id" = :user_id';
		$bindings = array("user_id" => 1,);

		$data = $cache->query($query, $bindings);
		$storedData = $cache->getQuery($query, $bindings);

		$this->assertEquals($data, $storedData);
	}

	/**
	 * Test if query with tags can be stored
	 *
	 * @return  void
	 */
	public function testQueryCanBeStoredInCacheWithTags()
	{
		$cache = new CacheManager;
		$query = 'SELECT * FROM ' . TABLE_ADMIN_USERS . ' WHERE user_id = :user_id';
		$bindings = ["user_id" => 1,];
		$tags = "testQuery";

		$data = $cache->query($query, $bindings, null, $tags);
		$storedData = $cache->getQuery($query, $bindings, $tags);

		$this->assertEquals($data, $storedData);
	}

	/**
	 * Test if query without tags can be removed
	 *
	 * @return  void
	 */
	public function testQueryWithoutTagCanBeRemoved()
	{
		$cache = new CacheManager;

		$query = 'SELECT * FROM ' . TABLE_ADMIN_USERS . ' WHERE user_id = :user_id';
		$bindings = ["user_id" => 1,];

		$data = $cache->query($query, $bindings);
		$cache->forgetQuery($query, $bindings);

		$this->assertNull($cache->getQuery($query, $bindings));
	}

	/**
	 * Test if query with tags can be removed
	 *
	 * @return  void
	 */
	public function testQueryWithTagCanBeRemoved()
	{
		$cache = new CacheManager;

		$query = 'SELECT * FROM ' . TABLE_ADMIN_USERS . ' WHERE user_id = :user_id';
		$bindings = ["user_id" => 1,];
		$tags = "testQuery";

		$cache->query($query, $bindings, null, $tags);
		$cache->forgetQuery($query, $bindings, $tags);

		$this->assertNull($cache->getQuery($query, $bindings, $tags));
	}

}