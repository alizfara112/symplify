<?php declare(strict_types=1);

namespace Symplify\BetterPhpDocParser\Tests\PhpDocInfo;

use Iterator;
use Nette\Utils\FileSystem;
use Symplify\BetterPhpDocParser\HttpKernel\BetterPhpDocParserKernel;
use Symplify\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Symplify\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class PhpDocInfoPrinterTest extends AbstractKernelTestCase
{
    /**
     * @var PhpDocInfoFactory
     */
    private $phpDocInfoFactory;

    /**
     * @var PhpDocInfoPrinter
     */
    private $phpDocInfoPrinter;

    protected function setUp(): void
    {
        $this->bootKernel(BetterPhpDocParserKernel::class);

        $this->phpDocInfoFactory = self::$container->get(PhpDocInfoFactory::class);
        $this->phpDocInfoPrinter = self::$container->get(PhpDocInfoPrinter::class);
    }

    /**
     * @dataProvider provideDocFilesForPrint()
     */
    public function testPrintFormatPreserving(string $docFilePath): void
    {
        $docComment = FileSystem::read($docFilePath);
        $phpDocInfo = $this->phpDocInfoFactory->createFrom($docComment);

        $this->assertSame(
            $docComment,
            $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo),
            'Caused in ' . $docFilePath
        );
    }

    public function provideDocFilesForPrint(): Iterator
    {
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc2.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc3.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc4.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc5.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc6.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc7.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc8.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc9.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc10.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc11.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc12.txt'];
        yield [__DIR__ . '/PhpDocInfoPrinterSource/doc13.txt'];
    }

    /**
     * @dataProvider provideDocFilesToEmpty()
     */
    public function testPrintFormatPreservingEmpty(string $docFilePath): void
    {
        $docComment = FileSystem::read($docFilePath);
        $phpDocInfo = $this->phpDocInfoFactory->createFrom($docComment);

        $this->assertEmpty($this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo));
    }

    public function provideDocFilesToEmpty(): Iterator
    {
        yield [__DIR__ . '/PhpDocInfoPrinterSource/empty-doc.txt'];
    }
}
