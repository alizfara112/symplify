<?php declare(strict_types=1);

namespace Symplify\SniffRunner\Report;

final class ErrorDataCollector
{
    /**
     * @var int
     */
    private $errorCount = 0;

    /**
     * @var int
     */
    private $fixableErrorCount = 0;

    /**
     * @var array[]
     */
    private $errorMessages = [];

    /**
     * @var ErrorMessageSorter
     */
    private $errorMessageSorter;

    public function __construct(ErrorMessageSorter $errorMessageSorter)
    {
        $this->errorMessageSorter = $errorMessageSorter;
    }

    public function getErrorCount() : int
    {
        return $this->errorCount;
    }

    public function getFixableErrorCount() : int
    {
        return $this->fixableErrorCount;
    }

    public function getUnfixableErrorCount() : int
    {
        return $this->errorCount - $this->fixableErrorCount;
    }

    public function getErrorMessages() : array
    {
        return $this->errorMessageSorter->sortByFileAndLine($this->errorMessages);
    }

    public function getUnfixableErrorMessages() : array
    {
        $unfixableErrorMessages = [];
        foreach ($this->getErrorMessages() as $file => $errorMessagesForFile) {
            $unfixableErrorMessagesForFile = $this->filterUnfixableErrorMessagesForFile($errorMessagesForFile);
            if (count($unfixableErrorMessagesForFile)) {
                $unfixableErrorMessages[$file] = $unfixableErrorMessagesForFile;
            }
        }

        return $unfixableErrorMessages;
    }

    public function addErrorMessage(
        string $filePath,
        string $message,
        int $line,
        string $sniffClass,
        array $data = [],
        bool $isFixable = false
    ) {
        $this->errorCount++;

        if ($isFixable) {
            $this->fixableErrorCount++;
        }

        $this->errorMessages[$filePath][] = [
            'line' => $line,
            'message' => $this->applyDataToMessage($message, $data),
            'sniffClass' => $sniffClass,
            'isFixable'  => $isFixable
        ];
    }

    private function applyDataToMessage(string $message, array $data) : string
    {
        if (count($data)) {
            $message = vsprintf($message, $data);
        }

        return $message;
    }

    private function filterUnfixableErrorMessagesForFile(array $errorMessagesForFile) : array
    {
        $unfixableErrorMessages = [];
        foreach ($errorMessagesForFile as $errorMessage) {
            if ($errorMessage['isFixable']) {
                continue;
            }

            $unfixableErrorMessages[] = $errorMessage;
        }

        return $unfixableErrorMessages;
    }
}
