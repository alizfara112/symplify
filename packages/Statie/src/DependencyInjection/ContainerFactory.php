<?php declare(strict_types=1);

namespace Symplify\Statie\DependencyInjection;

use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    public function create(): ContainerInterface
    {
        $appKernel = new AppKernel;
        $appKernel->boot();

        return $appKernel->getContainer();
    }
}
