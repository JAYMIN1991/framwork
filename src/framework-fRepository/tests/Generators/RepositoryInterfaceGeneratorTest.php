<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/12/16
 * Time: 7:44 PM
 */

namespace Flinnt\Repository\Test\Generators;


use Flinnt\Repository\Generators\RepositoryInterfaceGenerator;
use Flinnt\Repository\Test\TestCase;

class RepositoryInterfaceGeneratorTest extends TestCase
{
	public function testGetPath()
	{
		$repositoryGenerator = $this->getMockBuilder(RepositoryInterfaceGenerator::class)->setMethods(['getBasePath', 'getName'])->getMock();
		$repositoryGenerator->expects($this->once())->method('getBasePath')->willReturn("App");
		$repositoryGenerator->expects($this->once())->method('getName')->willReturn("Test");

		$this->assertEquals("App/Repositories/Contracts/TestRepo.php", $repositoryGenerator->getPath());
	}
}