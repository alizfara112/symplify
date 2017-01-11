<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\Console;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


final class ConsoleTest extends TestCase
{

	/**
	 * @var Application
	 */
	private $consoleApplication;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->consoleApplication = $container->getByType(Application::class);
		$this->consoleApplication->setAutoExit(FALSE);
		$this->entityManager = $container->getByType(EntityManagerInterface::class);
	}


	public function test()
	{
		$this->assertCount(0, $this->entityManager->getFilters()->getEnabledFilters());

		$stringInput = new StringInput('help');
		$this->consoleApplication->run($stringInput, new NullOutput);

		$this->assertCount(2, $this->entityManager->getFilters()->getEnabledFilters());
	}


	public function testEnablingOnlyOnce()
	{
		$stringInput = new StringInput('help');

		$this->assertCount(0, $this->entityManager->getFilters()->getEnabledFilters());

		$this->consoleApplication->run($stringInput, new NullOutput);

		$this->assertCount(2, $this->entityManager->getFilters()->getEnabledFilters());

		$this->consoleApplication->run($stringInput, new NullOutput);

		$this->assertCount(2, $this->entityManager->getFilters()->getEnabledFilters());
	}

}
