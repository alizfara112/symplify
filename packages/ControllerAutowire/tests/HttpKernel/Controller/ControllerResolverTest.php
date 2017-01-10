<?php declare(strict_types=1);

namespace Symplify\ControllerAutowire\Tests\HttpKernel\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symplify\ControllerAutowire\HttpKernel\Controller\ControllerResolver;

final class ControllerResolverTest extends TestCase
{
    /**
     * @var ControllerResolver
     */
    private $controllerResolver;

    protected function setUp()
    {
        $this->controllerResolver = $this->createControllerResolverWithMocks();
    }

    public function testGetController()
    {
        $request = new Request;
        $request->attributes->set('_controller', 'SomeController::someAction');

        $controller = $this->controllerResolver->getController($request);
        $this->assertNull($controller);
    }

    public function testGetArguments()
    {
        $this->assertNull(
            $this->controllerResolver->getArguments(new Request, 'missing')
        );
    }

    /**
     * @return ControllerResolver
     */
    private function createControllerResolverWithMocks()
    {
        $parentControllerResolverMock = $this->prophesize(ControllerResolverInterface::class);
        $containerMock = $this->prophesize(ContainerInterface::class);
        $controllerNameParser = $this->prophesize(ControllerNameParser::class);

        return new ControllerResolver(
            $parentControllerResolverMock->reveal(),
            $containerMock->reveal(),
            $controllerNameParser->reveal()
        );
    }
}
