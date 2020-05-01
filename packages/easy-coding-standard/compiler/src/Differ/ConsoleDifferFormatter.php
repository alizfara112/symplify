<?php

declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Compiler\Differ;

use Nette\Utils\Strings;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Mimics @see \Rector\ConsoleDiffer\Console\Formatter\DiffConsoleFormatter
 */
final class ConsoleDifferFormatter
{
    /**
     * @var string
     */
    private $template;

    public function __construct()
    {
        $this->template = sprintf(
            '<comment>    ---------- begin diff ----------</comment>%s%%s%s<comment>    ----------- end diff -----------</comment>',
            PHP_EOL,
            PHP_EOL
        );
    }

    public function format(string $diff): string
    {
        return $this->formatWithTemplate($diff, $this->template);
    }

    private function formatWithTemplate(string $diff, string $template): string
    {
        return sprintf($template, implode(PHP_EOL, array_map(function (string $string): string {
            // make "+" lines green
            $string = Strings::replace($string, '#^(\+.*)#', '<fg=green>$1</fg=green>');
            // make "-" lines red
            $string = Strings::replace($string, '#^(\-.*)#', '<fg=red>$1</fg=red>');
            // make "@ note" lines cyan
            $string = Strings::replace($string, '#^(@.*)#', '<fg=cyan>$1</fg=cyan>');

            if ($string === ' ') {
                $string = rtrim($string);
            }

            return $string;
        }, Strings::split(OutputFormatter::escape(rtrim($diff)), "#\n\r|\n#"))));
    }
}
