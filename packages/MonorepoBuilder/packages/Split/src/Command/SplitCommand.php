<?php declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Split\Command;

use Nette\Utils\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Split\Configuration\RepositoryGuard;
use Symplify\MonorepoBuilder\Split\PackageToRepositorySplitter;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class SplitCommand extends Command
{
    /**
     * @var RepositoryGuard
     */
    private $repositoryGuard;

    /**
     * @var string[]
     */
    private $directoriesToRepositories = [];

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var PackageToRepositorySplitter
     */
    private $packageToRepositorySplitter;

    /**
     * @param string[] $directoriesToRepositories
     */
    public function __construct(
        RepositoryGuard $repositoryGuard,
        array $directoriesToRepositories,
        string $rootDirectory,
        PackageToRepositorySplitter $packageToRepositorySplitter
    ) {
        parent::__construct();

        $this->repositoryGuard = $repositoryGuard;
        $this->directoriesToRepositories = $directoriesToRepositories;
        $this->rootDirectory = $rootDirectory;
        $this->packageToRepositorySplitter = $packageToRepositorySplitter;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repositoryGuard->ensureIsRepositoryDirectory($this->rootDirectory);

        $isVerbose = $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;

        $this->packageToRepositorySplitter->splitDirectoriesToRepositories(
            $this->directoriesToRepositories,
            $this->rootDirectory,
            $this->getSubsplitDirectory(),
            $isVerbose
        );

        // success
        return 0;
    }

    private function getSubsplitDirectory(): string
    {
        $tempDirectory = sys_get_temp_dir() . '/_subsplit';

        // clean it
        FileSystem::delete($tempDirectory);

        # @todo: should be in config?
        return $tempDirectory;
    }
}
