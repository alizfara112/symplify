<?php

declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Reporter;

use Symplify\EasyCodingStandard\Console\Output\OutputFormatterCollector;
use Symplify\EasyCodingStandard\Parallel\ValueObject\Bridge;
use Symplify\EasyCodingStandard\SniffRunner\ValueObject\Error\CodingStandardError;
use Symplify\EasyCodingStandard\ValueObject\Configuration;
use Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult;
use Symplify\EasyCodingStandard\ValueObject\Error\FileDiff;
use Symplify\EasyCodingStandard\ValueObject\Error\SystemError;

final class ProcessedFileReporter
{
    public function __construct(
        private OutputFormatterCollector $outputFormatterCollector,
    ) {
    }

    /**
     * @param array<string, array<SystemError|FileDiff|CodingStandardError>> $errorsAndDiffs
     */
    public function report(array $errorsAndDiffs, Configuration $configuration): int
    {
        $outputFormat = $configuration->getOutputFormat();
        $outputFormatter = $this->outputFormatterCollector->getByName($outputFormat);

        /** @var SystemError[] $systemErrors */
        $systemErrors = $errorsAndDiffs[Bridge::SYSTEM_ERRORS] ?? [];

        /** @var FileDiff[] $fileDiffs */
        $fileDiffs = $errorsAndDiffs[Bridge::FILE_DIFFS] ?? [];

        /** @var CodingStandardError[] $codingStandardErrors */
        $codingStandardErrors = $errorsAndDiffs[Bridge::CODING_STANDARD_ERRORS] ?? [];

        $errorAndDiffResult = new ErrorAndDiffResult($codingStandardErrors, $fileDiffs, $systemErrors);
        return $outputFormatter->report($errorAndDiffResult, $configuration);
    }
}
