<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/12/16
 * Time: 7:37 PM
 */

namespace Flinnt\Repository\Test\Generators;


use Flinnt\Repository\Generators\CriteriaGenerator;
use Flinnt\Repository\Test\TestCase;

class CriteriaGeneratorTest extends TestCase
{
	public function testGetPath()
	{
		$repositoryGenerator = $this->getMockBuilder(CriteriaGenerator::class)->setMethods(['getBasePath', 'getName'])->getMock();
		$repositoryGenerator->expects($this->once())->method('getBasePath')->willReturn("App");
		$repositoryGenerator->expects($this->once())->method('getName')->willReturn("Test");

		$this->assertEquals("App/Repositories/Criteria/TestCrit.php", $repositoryGenerator->getPath());
	}
}