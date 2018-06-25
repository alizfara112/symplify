<?php declare(strict_types=1);

namespace Symplify\ChangelogLinker\FileSystem;

use Nette\Utils\FileSystem;
use Symplify\ChangelogLinker\Analyzer\LinksAnalyzer;
use Symplify\ChangelogLinker\LinkAppender;

final class ChangelogFileSystem
{
    /**
     * @var LinkAppender
     */
    private $linkAppender;

    /**
     * @var ChangelogFileSystemGuard
     */
    private $changelogFileSystemGuard;

    /**
     * @var LinksAnalyzer
     */
    private $linksAnalyzer;

    public function __construct(
        LinkAppender $linkAppender,
        ChangelogFileSystemGuard $changelogFileSystemGuard,
        LinksAnalyzer $linksAnalyzer
    ) {
        $this->linkAppender = $linkAppender;
        $this->changelogFileSystemGuard = $changelogFileSystemGuard;
        $this->linksAnalyzer = $linksAnalyzer;
    }

    public function readChangelog(): string
    {
        $changelogFilePath = $this->getChangelogFilePath();
        $this->changelogFileSystemGuard->ensureFileExists($changelogFilePath);

        return FileSystem::read($changelogFilePath);
    }

    public function storeChangelog(string $content): void
    {
        foreach ($this->linkAppender->getLinksToAppend() as $linkToAppend) {
            $content .= PHP_EOL . $linkToAppend;
        }

        FileSystem::write($this->getChangelogFilePath(), $content);
    }

    public function addToChangelogOnPlaceholder(string $newContent, string $placeholder): void
    {
        $changelogContent = $this->readChangelog();

        $this->linksAnalyzer->analyzeContent($changelogContent);

        $this->changelogFileSystemGuard->ensurePlaceholderIsPresent($changelogContent, $placeholder);

        $contentToWrite = sprintf(
            '%s%s%s<!-- dumped content start -->%s%s<!-- dumped content end -->',
            $placeholder,
            PHP_EOL,
            PHP_EOL,
            PHP_EOL,
            $newContent
        );

        $updatedChangelogContent = str_replace($placeholder, $contentToWrite, $changelogContent);

        $this->storeChangelog($updatedChangelogContent);
    }

    private function getChangelogFilePath(): string
    {
        return getcwd() . '/CHANGELOG.md';
    }
}
