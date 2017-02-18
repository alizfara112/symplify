<?php declare(strict_types=1);

namespace Symplify\SniffRunner\Validator;

use Symplify\SniffRunner\Exception\Configuration\OptionResolver\GroupNotFoundException;
use Symplify\SniffRunner\Repository\SniffRepository;

final class GroupValidator
{
    /**
     * @var SniffRepository
     */
    private $sniffRepository;

    public function __construct(SniffRepository $sniffRepository)
    {
        $this->sniffRepository = $sniffRepository;
    }

    public function ensureGroupsExist(array $groups): void
    {
        $availableGroups = $this->sniffRepository->getGroups();
        foreach ($groups as $group) {
            if (!array_key_exists($group, $availableGroups)) {
                throw new GroupNotFoundException(sprintf(
                    'Standard "%s" is not supported. Pick one of: %s.',
                    $group,
                    implode(array_keys($availableGroups), ', ')
                ));
            }
        }
    }
}
