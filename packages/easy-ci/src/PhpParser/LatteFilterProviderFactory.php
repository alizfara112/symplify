<?php

declare(strict_types=1);

namespace Symplify\EasyCI\PhpParser;

use PhpParser\Builder\Class_ as ClassBuilder;
use PhpParser\Builder\Namespace_;
use PhpParser\Node\Const_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\Node\Stmt\Nop;
use PhpParser\PrettyPrinter\Standard;
use Symplify\EasyCI\NodeFactory\GetNameClassMethodFactory;
use Symplify\EasyCI\NodeFactory\InvokeClassMethodFactory;
use Symplify\EasyCI\ValueObject\ClassMethodName;

/**
 * @see \Symplify\EasyCI\Tests\PhpParser\LatteFilterProviderGenerator\LatteFilterProviderGeneratorTest
 */
final class LatteFilterProviderFactory
{
    /**
     * @var string
     */
    private const LATTE_FILTER_PROVIDER_INTERFACE_NAME = 'App\Contract\Latte\FilterProviderInterface';

    /**
     * @var string
     */
    private const NAMESPACE_NAME = 'App\Latte\FilterProvider';

    public function __construct(
        private Standard $printerStandard,
        private InvokeClassMethodFactory $invokeClassMethodFactory,
        private GetNameClassMethodFactory $getNameClassMethodFactory
    ) {
    }

    public function createFromClassMethodName(ClassMethodName $classMethodName): string
    {
        $namespaceBuilder = new Namespace_(self::NAMESPACE_NAME);

        $filterProviderClass = $this->createFilterProviderClass($classMethodName);

        $declare = new Declare_([new DeclareDeclare('strict_types', new LNumber(1))]);
        $namespaceBuilder->addStmt($filterProviderClass);
        $namespace = $namespaceBuilder->getNode();

        $stmts = [$declare, new Nop(), $namespace];
        return $this->printerStandard->prettyPrintFile($stmts) . PHP_EOL;
    }

    private function createFilterNameConst(ClassMethodName $classMethodName): ClassConst
    {
        $const = new Const_('FILTER_NAME', new String_($classMethodName->getMethod()));
        $classConst = new ClassConst([$const]);
        $classConst->flags |= Class_::MODIFIER_PUBLIC;

        return $classConst;
    }

    private function createFilterProviderClass(ClassMethodName $classMethodName): Class_
    {
        $class = new ClassBuilder($classMethodName->getFilterProviderClassName());
        $class->makeFinal();
        $class->implement(new FullyQualified(self::LATTE_FILTER_PROVIDER_INTERFACE_NAME));

        // add filter name constant
        $classConst = $this->createFilterNameConst($classMethodName);
        $class->addStmt($classConst);

        // add getName method
        $getNameClassMethod = $this->getNameClassMethodFactory->create();
        $class->addStmt($getNameClassMethod);

        // add __invoke method
        $invokeClassMethod = $this->invokeClassMethodFactory->create($classMethodName);
        $class->addStmt($invokeClassMethod);

        return $class->getNode();
    }
}
