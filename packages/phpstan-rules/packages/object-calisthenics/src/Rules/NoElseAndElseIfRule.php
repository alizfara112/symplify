<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\ObjectCalisthenics\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PHPStan\Analyser\Scope;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;

/**
 * @see https://github.com/object-calisthenics/phpcs-calisthenics-rules#2-do-not-use-else-keyword
 *
 * @see \Symplify\PHPStanRules\ObjectCalisthenics\Tests\Rules\NoElseAndElseIfRule\NoElseAndElseIfRuleTest
 */
final class NoElseAndElseIfRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const MESSAGE = 'Do not use "else/elseif". Prefer early return statement instead.';

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Else_::class, ElseIf_::class];
    }

    /**
     * @param Else_|ElseIf_ $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        return [self::MESSAGE];
    }
}
