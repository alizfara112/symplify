<?php

declare(strict_types=1);

namespace Symplify\ControllerAutowire\Tests\HttpKernel\Controller\ControllerFinderSource;

class SomeController
{
    /**
     * @var SomeService
     */
    private $someService;

    public function __construct(SomeService $someService)
    {
        $this->someService = $someService;
    }

    /**
     * @return SomeService
     */
    public function getSomeService()
    {
        return $this->someService;
    }
}
