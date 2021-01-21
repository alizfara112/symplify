<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\ForbiddenThisArgumentRule;

use Iterator;
use PHPStan\Rules\Rule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;
use Symplify\PHPStanRules\Rules\ForbiddenThisArgumentRule;

final class ForbiddenThisArgumentRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/SkipNotVariable.php', []];
        yield [__DIR__ . '/Fixture/SkipNotThis.php', []];
        yield [__DIR__ . '/Fixture/SkipExtendsKernel.php', []];
        yield [__DIR__ . '/Fixture/ThisArgument.php', [[ForbiddenThisArgumentRule::ERROR_MESSAGE, 11]]];
        yield [__DIR__ . '/Fixture/ThisArgumentCopy.php', [[ForbiddenThisArgumentRule::ERROR_MESSAGE, 12]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            ForbiddenThisArgumentRule::class,
            __DIR__ . '/../../../config/symplify-rules.neon'
        );
    }
}
