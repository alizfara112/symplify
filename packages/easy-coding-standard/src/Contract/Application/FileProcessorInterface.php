<?php

declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Contract\Application;

use Symplify\EasyCodingStandard\SniffRunner\ValueObject\Error\CodingStandardError;
use Symplify\EasyCodingStandard\ValueObject\Configuration;
use Symplify\EasyCodingStandard\ValueObject\Error\FileDiff;
use Symplify\SmartFileSystem\SmartFileInfo;

interface FileProcessorInterface
{
    public function processFileToString(SmartFileInfo $smartFileInfo): string;

    /**
     * @return array<string, array<FileDiff|CodingStandardError>>
     */
    public function processFile(SmartFileInfo $smartFileInfo, Configuration $configuration): array;

    /**
     * @return object[]
     */
    public function getCheckers(): array;
}
