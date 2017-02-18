<?php declare(strict_types=1);

namespace Symplify\PHP7_CodeSniffer\Tests\File;

use PHP_CodeSniffer\Files\File as BaseFile;
use PHPUnit\Framework\TestCase;
use Symplify\PHP7_CodeSniffer\Application\Fixer;
use Symplify\PHP7_CodeSniffer\Contract\File\FileInterface;
use Symplify\PHP7_CodeSniffer\DI\ContainerFactory;
use Symplify\PHP7_CodeSniffer\File\File;
use Symplify\PHP7_CodeSniffer\File\FileFactory;

final class FileFactoryTest extends TestCase
{
    /**
     * @var FileFactory
     */
    private $fileFactory;

    protected function setUp()
    {
        $container = (new ContainerFactory())->create();
        $this->fileFactory = $container->getByType(FileFactory::class);
    }

    public function testCreate()
    {
        $file = $this->fileFactory->create(__DIR__ . '/FileFactorySource/SomeFile.php', false);
        $this->assertInstanceOf(File::class, $file);
        $this->assertInstanceOf(BaseFile::class, $file);
        $this->assertInstanceOf(FileInterface::class, $file);
        $this->assertInstanceOf(Fixer::class, $file->fixer);
    }

    /**
     * @expectedException \Nette\FileNotFoundException
     */
    public function testCreateFromNotFile()
    {
        $this->fileFactory->create(__DIR__, false);
    }
}
