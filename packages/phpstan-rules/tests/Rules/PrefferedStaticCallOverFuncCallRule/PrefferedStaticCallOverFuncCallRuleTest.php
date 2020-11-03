<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\PrefferedStaticCallOverFuncCallRule;

use Iterator;
use Nette\Utils\Strings;
use PHPStan\Rules\Rule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;
use Symplify\PHPStanRules\Rules\PrefferedStaticCallOverFuncCallRule;

final class PrefferedStaticCallOverFuncCallRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        $errorMessage = sprintf(
            PrefferedStaticCallOverFuncCallRule::ERROR_MESSAGE,
            Strings::class,
            'match',
            'preg_match'
        );

        yield [__DIR__ . '/Fixture/PregMatchCalled.php', [[$errorMessage, 11]]];

        yield [__DIR__ . '/Fixture/SkipSelfCall.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            PrefferedStaticCallOverFuncCallRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
