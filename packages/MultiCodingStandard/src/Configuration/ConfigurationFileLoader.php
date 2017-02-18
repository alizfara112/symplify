<?php declare(strict_types=1);

namespace Symplify\MultiCodingStandard\Configuration;

use Nette\Utils\Json;
use Symplify\MultiCodingStandard\Exception\Configuration\ConfigurationFileNotFoundException;

final class ConfigurationFileLoader
{
    /**
     * @var string
     */
    private const CONFIGURATION_FILE = 'coding-standard.json';

    /**
     * @var string
     */
    private $configurationFile;

    public function __construct(string $configurationFile = null)
    {
        $this->configurationFile = $configurationFile ?: getcwd().DIRECTORY_SEPARATOR. self::CONFIGURATION_FILE;
    }

    public function load() : array
    {
        $this->ensureFileExists($this->configurationFile);

        $fileContent = file_get_contents($this->configurationFile);

        return Json::decode($fileContent, true);
    }

    private function ensureFileExists(string $multiCsJsonFile) : void
    {
        if (!file_exists($multiCsJsonFile)) {
            throw new ConfigurationFileNotFoundException(
                sprintf(
                    'File "%s" was not found in "%s". Did you forget to create it?',
                    self::CONFIGURATION_FILE,
                    realpath(dirname($multiCsJsonFile)).'/'.basename($multiCsJsonFile)
                )
            );
        }
    }
}
