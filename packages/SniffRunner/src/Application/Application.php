<?php declare(strict_types=1);

namespace Symplify\SniffRunner\Application;

use PHP_CodeSniffer\Sniffs\Sniff;
use Symplify\MultiCodingStandard\Application\Command\RunApplicationCommand;
use Symplify\MultiCodingStandard\Contract\Application\ApplicationInterface;
use Symplify\SniffRunner\EventDispatcher\SniffDispatcher;
use Symplify\SniffRunner\File\Provider\FilesProvider;
use Symplify\SniffRunner\Legacy\LegacyCompatibilityLayer;
use Symplify\SniffRunner\Sniff\Factory\SniffFactory;
use Symplify\SniffRunner\Sniff\SniffCollectionResolver;

final class Application implements ApplicationInterface
{
    /**
     * @var SniffDispatcher
     */
    private $sniffDispatcher;

    /**
     * @var FilesProvider
     */
    private $filesProvider;

    /**
     * @var FileProcessor
     */
    private $fileProcessor;

    /**
     * @var SniffFactory
     */
    private $sniffFactory;

    /**
     * @var SniffCollectionResolver
     */
    private $sniffCollectionResolver;

    public function __construct(
        SniffDispatcher $sniffDispatcher,
        FilesProvider $sourceFilesProvider,
        FileProcessor $fileProcessor,
        SniffFactory $sniffFactory,
        SniffCollectionResolver $sniffCollectionResolver
    ) {
        $this->sniffDispatcher = $sniffDispatcher;
        $this->filesProvider = $sourceFilesProvider;
        $this->fileProcessor = $fileProcessor;
        $this->sniffFactory = $sniffFactory;
        $this->sniffCollectionResolver = $sniffCollectionResolver;

        LegacyCompatibilityLayer::add();
    }

    public function runCommand(RunApplicationCommand $command) : void
    {
        $sniffClasses = $this->sniffCollectionResolver->resolve(
            $command->getStandards(), $command->getSniffs(), $command->getExcludedSniffs()
        );
        $sniffs = $this->createSniffsFromSniffClasses($sniffClasses);
        $this->registerSniffsToSniffDispatcher($sniffs);

        $this->runForSource($command->getSources(), $command->isFixer());
    }

    /**
     * @param string[] $sniffClasses
     * @return Sniff[]
     */
    private function createSniffsFromSniffClasses(array $sniffClasses) : array
    {
        $sniffs = [];
        foreach ($sniffClasses as $sniffClass) {
            $sniffs[] = $this->sniffFactory->create($sniffClass);
        }
        return $sniffs;
    }

    /**
     * @param Sniff[] $sniffs
     */
    private function registerSniffsToSniffDispatcher(array $sniffs) : void
    {
        $this->sniffDispatcher->addSniffListeners($sniffs);
    }

    private function runForSource(array $source, bool $isFixer) : void
    {
        $files = $this->filesProvider->getFilesForSource($source, $isFixer);
        $this->fileProcessor->processFiles($files, $isFixer);
    }
}
