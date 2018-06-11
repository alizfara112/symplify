<?php declare(strict_types=1);

namespace Symplify\ChangelogLinker\ChangeTree;

final class Change
{
    /**
     * @var string
     */
    public const UNKNOWN_PACKAGE = 'Unknown Package';

    /**
     * @var string
     */
    public const UNKNOWN_CATEGORY= 'Unknown Category';

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $package;
    /**
     * @var string
     */
    private $messageWithoutPackage;

    public function __construct(string $message, string $category, string $package, string $messageWithoutPackage)
    {
        $this->message = $message;
        $this->category = $category;
        $this->package = $package;
        $this->messageWithoutPackage = $messageWithoutPackage;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function getMessageWithoutPackage(): string
    {
        return $this->messageWithoutPackage;
    }
}
