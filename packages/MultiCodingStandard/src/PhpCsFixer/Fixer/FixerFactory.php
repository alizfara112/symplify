<?php declare(strict_types=1);

namespace Symplify\MultiCodingStandard\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\FixerInterface;
use Symplify\MultiCodingStandard\Configuration\ConfigurationResolverFactory;

final class FixerFactory
{
    /**
     * @var ConfigurationResolverFactory
     */
    private $configurationResolverFactory;

    public function __construct(ConfigurationResolverFactory $configurationResolverFactory)
    {
        $this->configurationResolverFactory = $configurationResolverFactory;
    }

    /**
     * @return FixerInterface[]
     */
    public function createFromRulesAndExcludedRules(array $rules, array $excludedRules)
    {
        if (!count($rules)) {
            return [];
        }

        $configuration = $this->configurationResolverFactory->createFromRulesAndExcludedRules($rules, $excludedRules);
        return $configuration->getFixers();
    }
}
