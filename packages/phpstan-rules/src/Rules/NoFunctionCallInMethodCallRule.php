<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Rules;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;

/**
 * @see \Symplify\PHPStanRules\Tests\Rules\NoFunctionCallInMethodCallRule\NoFunctionCallInMethodCallRuleTest
 */
final class NoFunctionCallInMethodCallRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Separate function "%s()" in method call to standalone row to improve readability';

    /**
     * @var string[]
     */
    private const ALLOWED_FUNC_CALL_NAMES = ['getcwd', 'sys_get_temp_dir'];

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        $messages = [];

        foreach ($node->args as $arg) {
            if (! $arg->value instanceof FuncCall) {
                continue;
            }

            $funcCallName = $this->resolveFuncCallName($arg);

            if (Strings::contains($funcCallName, '\\')) {
                continue;
            }

            if (in_array($funcCallName, self::ALLOWED_FUNC_CALL_NAMES, true)) {
                continue;
            }

            $messages[] = sprintf(self::ERROR_MESSAGE, $funcCallName);
        }

        return $messages;
    }

    private function resolveFuncCallName(Arg $arg): string
    {
        /** @var FuncCall $funcCall */
        $funcCall = $arg->value;
        if ($funcCall->name instanceof Expr) {
            return '*dynamic*';
        }

        return (string) $funcCall->name;
    }
}
