<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/12/16
 * Time: 3:51 PM
 */

namespace Flinnt\Repository\Test\Generators;


use Flinnt\Repository\Generators\RepositoryGenerator;
use Flinnt\Repository\Test\TestCase;

class RepositoryGeneratorTest extends TestCase
{
	public function testGetPath()
	{
		$repositoryGenerator = $this->getMockBuilder(RepositoryGenerator::class)->setMethods(['getBasePath', 'getName'])->getMock();
		$repositoryGenerator->expects($this->once())->method('getBasePath')->willReturn("App");
		$repositoryGenerator->expects($this->once())->method('getName')->willReturn("Test");

		$this->assertEquals("App/Repositories/Test.php", $repositoryGenerator->getPath());
	}

	public function testGetReplacement()
	{
		$repositoryGenerator = $this->getMockBuilder(RepositoryGenerator::class)->setMethods(['getBasePath', 'getName'])->getMock();
		$repositoryGenerator->expects($this->any())->method('getName')->willReturn("Test");
		$replacement = $repositoryGenerator->getReplacements();

		$this->assertArrayHasKey('repository', $replacement);
		$this->assertEquals('App\Repositories\Contracts\TestRepo;', $replacement['repository']);
	}
}