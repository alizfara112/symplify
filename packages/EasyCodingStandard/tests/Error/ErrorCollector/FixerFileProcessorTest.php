<?php declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Tests\Error\ErrorCollector;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use Symplify\EasyCodingStandard\ChangedFilesDetector\ChangedFilesDetector;
use Symplify\EasyCodingStandard\DependencyInjection\ContainerFactory;
use Symplify\EasyCodingStandard\Error\Error;
use Symplify\EasyCodingStandard\Error\ErrorAndDiffCollector;
use Symplify\EasyCodingStandard\FixerRunner\Application\FixerFileProcessor;

final class FixerFileProcessorTest extends TestCase
{
    /**
     * @var ErrorAndDiffCollector
     */
    private $errorAndDiffCollector;

    /**
     * @var FixerFileProcessor
     */
    private $fixerFileProcessor;

    protected function setUp(): void
    {
        $container = (new ContainerFactory())->createWithConfig(
            __DIR__ . '/FixerRunnerSource/phpunit-fixer-config.neon'
        );

        $this->errorAndDiffCollector = $container->get(ErrorAndDiffCollector::class);
        $this->fixerFileProcessor = $container->get(FixerFileProcessor::class);
    }

    public function test(): void
    {
        $this->runFileProcessor();

        $this->assertSame(0, $this->errorAndDiffCollector->getErrorCount());
        $this->assertSame(1, $this->errorAndDiffCollector->getFileDiffsCount());
    }

    private function runFileProcessor(): void
    {
        $fileInfo = new SplFileInfo(__DIR__ . '/ErrorCollectorSource/NotPsr2Class.php.inc', '', '');

        $this->fixerFileProcessor->processFile($fileInfo);
    }
}
