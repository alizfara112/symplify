<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\DI;

use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use Zenify\DoctrineFilters\Exception\DefinitionForTypeNotFoundException;


final class DefinitionFinder
{

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;


	public function __construct(ContainerBuilder $containerBuilder)
	{
		$this->containerBuilder = $containerBuilder;
	}


	public function getDefinitionByType(string $type) : ServiceDefinition
	{
		$this->containerBuilder->prepareClassList();

		if ($name = $this->containerBuilder->getByType($type)) {
			return $this->containerBuilder->getDefinition($name);
		}

		foreach ($this->containerBuilder->findByType($type) as $definition) {
			return $definition;
		}

		throw new DefinitionForTypeNotFoundException(
			sprintf('Definition for type "%s" was not found.', $type)
		);
	}


	public function getServiceNameByType(string $type) : string
	{
		$this->containerBuilder->prepareClassList();

		if ($name = $this->containerBuilder->getByType($type)) {
			return $name;
		}

		foreach ($this->containerBuilder->findByType($type) as $name => $definition) {
			return $name;
		}

		throw new DefinitionForTypeNotFoundException(
			sprintf('Definition for type "%s" was not found.', $type)
		);
	}

}
