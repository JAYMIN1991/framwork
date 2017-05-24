<?php

/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/12/16
 * Time: 6:01 PM
 */
namespace Flinnt\Test\Core\DBLog;

use Flinnt\Core\DBLog\DBLog;
use Flinnt\Core\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test the functionality of DBLog.
 *
 * Class DBLogTest
 *
 * @see     DBLog
 * @package Flinnt\Test\Core\DBLog
 */
class DBLogTest extends TestCase
{

	use DatabaseTransactions;

	/**
	 * Test the save method of DBLog if user is not logged in
	 */
	public function testSaveLogUserIdIsZeroIfUserIsNotLoggedIn()
	{

		$dbLog = DBLog::getInstance();
		$id = $dbLog->save("test", "0", "testing", "testing");
		$this->seeInDatabase(TABLE_BACKOFFICE_LOG, ["id" => $id, "user_id" => 0]);
	}

	/**
	 * Test the save method of DBLog if user is logged in
	 */
	public function testSaveLogUserIdExistIfUserIsLoggedIn()
	{
		$user_id = 2;

		$dbLog = DBLog::getInstance();
		$id = $dbLog->save("test", "0", "testing", "testing", $user_id);
		$this->seeInDatabase(TABLE_BACKOFFICE_LOG, ["id" => $id, "user_id" => $user_id]);
	}

	/**
	 * Test The sales Team Search Method With Extra Params  //TODO:: Haven't run this case yet
	 */
	public function testSalesTeamSearchWithQueryString()
	{
		$url = "https://backoffice.local/sales/sales-team?first_name=&city_name=&is_left=&last_name=&parent_member_id=&btnsearch=";
		$user_id = 1;
		$dbLog = DBLog::getInstance();
		$exta_param = array("extra_param1" => 1, "extra_param2" => 2);
		$id = $dbLog->save(LOG_MODULE_SALES_TEAM, NULL, "search", $url, $exta_param);

		$this->seeInDatabase(TABLE_BACKOFFICE_LOG, ["id" => $id, "user_id" => $user_id]);
	}
}