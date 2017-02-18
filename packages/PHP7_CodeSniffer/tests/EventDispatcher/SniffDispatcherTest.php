<?php declare(strict_types=1);

namespace Symplify\PHP7_CodeSniffer\Tests\EventDispatcher;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff;
use PHPUnit\Framework\TestCase;
use Symplify\PHP7_CodeSniffer\DI\ContainerFactory;
use Symplify\PHP7_CodeSniffer\EventDispatcher\Event\CheckFileTokenEvent;
use Symplify\PHP7_CodeSniffer\EventDispatcher\SniffDispatcher;

final class SniffDispatcherTest extends TestCase
{
    /**
     * @var SniffDispatcher
     */
    private $sniffDispatcher;

    protected function setUp()
    {
        $container = (new ContainerFactory())->create();
        $this->sniffDispatcher = $container->getByType(SniffDispatcher::class);
    }

    public function testAddSniffListeners()
    {
        $sniffs = [new ClassDeclarationSniff()];
        $this->sniffDispatcher->addSniffListeners($sniffs);

        $this->assertCount(3, $this->sniffDispatcher->getListeners());
        $this->assertCount(1, $this->sniffDispatcher->getListeners(T_CLASS));
    }

    public function testDispatch()
    {
        $sniffs = [new ClassDeclarationSniff()];
        $this->sniffDispatcher->addSniffListeners($sniffs);

        $fileMock = $this->prophesize(File::class)
            ->reveal();

        $event = new CheckFileTokenEvent($fileMock, 5);
        $this->sniffDispatcher->dispatch(T_CLASS, $event);
    }
}
