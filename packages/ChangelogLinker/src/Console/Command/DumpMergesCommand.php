<?php declare(strict_types=1);

namespace Symplify\ChangelogLinker\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\ChangelogLinker\Analyzer\IdsAnalyzer;
use Symplify\ChangelogLinker\ChangelogDumper;
use Symplify\ChangelogLinker\ChangelogLinker;
use Symplify\ChangelogLinker\ChangeTree\ChangeResolver;
use Symplify\ChangelogLinker\Configuration\Option;
use Symplify\ChangelogLinker\Console\Input\PriorityResolver;
use Symplify\ChangelogLinker\FileSystem\ChangelogFileSystem;
use Symplify\ChangelogLinker\Github\GithubApi;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

/**
 * @inspired by https://github.com/weierophinney/changelog_generator
 */
final class DumpMergesCommand extends Command
{
    /**
     * @inspiration markdown comment: https://gist.github.com/jonikarppinen/47dc8c1d7ab7e911f4c9#gistcomment-2109856
     * @var string
     */
    private const CHANGELOG_PLACEHOLDER_TO_WRITE = '<!-- changelog-linker -->';

    /**
     * @var GithubApi
     */
    private $githubApi;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var IdsAnalyzer
     */
    private $idsAnalyzer;

    /**
     * @var ChangelogDumper
     */
    private $changelogDumper;

    /**
     * @var ChangelogFileSystem
     */
    private $changelogFileSystem;

    /**
     * @var PriorityResolver
     */
    private $priorityResolver;

    /**
     * @var ChangeResolver
     */
    private $changeResolver;

    /**
     * @var ChangelogLinker
     */
    private $changelogLinker;

    public function __construct(
        GithubApi $githubApi,
        SymfonyStyle $symfonyStyle,
        IdsAnalyzer $idsAnalyzer,
        ChangelogDumper $changelogDumper,
        ChangelogLinker $changelogLinker,
        ChangelogFileSystem $changelogFileSystem,
        PriorityResolver $priorityResolver,
        ChangeResolver $changeResolver
    ) {
        parent::__construct();
        $this->githubApi = $githubApi;
        $this->symfonyStyle = $symfonyStyle;
        $this->idsAnalyzer = $idsAnalyzer;
        $this->changelogDumper = $changelogDumper;
        $this->changelogLinker = $changelogLinker;
        $this->changelogFileSystem = $changelogFileSystem;
        $this->priorityResolver = $priorityResolver;
        $this->changeResolver = $changeResolver;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription(
            'Scans repository merged PRs, that are not in the CHANGELOG.md yet, and dumps them in changelog format.'
        );
        $this->addOption(
            Option::IN_CATEGORIES,
            null,
            InputOption::VALUE_NONE,
            'Print in Added/Changed/Fixed/Removed - detected from "Add", "Fix", "Removed" etc. keywords in merge title.'
        );

        $this->addOption(
            Option::IN_PACKAGES,
            null,
            InputOption::VALUE_NONE,
            'Print in groups in package names - detected from "[PackageName]" in merge title.'
        );

        $this->addOption(
            Option::IN_TAGS,
            null,
            InputOption::VALUE_NONE,
            'Print withs tags - detected from date of merge.'
        );

        $this->addOption(
            Option::DRY_RUN,
            null,
            InputOption::VALUE_NONE,
            'Print out to the output instead of writing directly into CHANGELOG.md.'
        );

        $this->addOption(
            Option::TOKEN,
            null,
            InputOption::VALUE_REQUIRED,
            'Github Token to overcome request limit.'
        );

        $this->addOption(Option::LINKIFY, null, InputOption::VALUE_NONE, 'Decorate content with links.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = $this->changelogFileSystem->readChangelog();

        $highestIdInChangelog = $this->idsAnalyzer->getHighestIdInChangelog($content);

        if ($input->getOption(Option::TOKEN)) {
            $this->githubApi->authorizeWithToken($input->getOption(Option::TOKEN));
        }

        $pullRequests = $this->githubApi->getClosedPullRequestsSinceId($highestIdInChangelog);
        if (count($pullRequests) === 0) {
            $this->symfonyStyle->note(
                sprintf('There are no new pull requests to be added since ID "%d".', $highestIdInChangelog)
            );

            // success
            return 0;
        }

        $sortPriority = $this->priorityResolver->resolveFromInput($input);

        $changes = $this->changeResolver->resolveSortedChangesFromPullRequestsWithSortPriority(
            $pullRequests,
            $sortPriority
        );

        $content = $this->changelogDumper->reportChangesWithHeadlines(
            $changes,
            $input->getOption(Option::IN_CATEGORIES),
            $input->getOption(Option::IN_PACKAGES),
            $input->getOption(Option::IN_TAGS),
            $sortPriority
        );

        if ($input->getOption(Option::DRY_RUN)) {
            if ($input->getOption(Option::LINKIFY)) {
                $content = $this->changelogLinker->processContentWithLinkAppends($content);
            }

            $this->symfonyStyle->writeln($content);

            // success
            return 0;
        }

        if ($input->getOption(Option::LINKIFY)) {
            $content = $this->changelogLinker->processContent($content);
        }

        $this->changelogFileSystem->addToChangelogOnPlaceholder($content, self::CHANGELOG_PLACEHOLDER_TO_WRITE);

        $this->symfonyStyle->success('The CHANGELOG.md was updated');

        // success
        return 0;
    }
}
