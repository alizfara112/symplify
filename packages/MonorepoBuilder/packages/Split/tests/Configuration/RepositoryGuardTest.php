<?php declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Split\Tests\Configuration;

use Iterator;
use Symplify\MonorepoBuilder\Split\Configuration\RepositoryGuard;
use Symplify\MonorepoBuilder\Split\Exception\InvalidRepositoryFormatException;
use Symplify\MonorepoBuilder\Split\Tests\AbstractContainerAwareTestCase;

final class RepositoryGuardTest extends AbstractContainerAwareTestCase
{
    /**
     * @var RepositoryGuard
     */
    private $repositoryGuard;

    protected function setUp(): void
    {
        $this->repositoryGuard = $this->container->get(RepositoryGuard::class);
    }

    /**
     * @dataProvider provideDataForEnsureIsRepository()
     * @doesNotPerformAssertions
     */
    public function testValid(string $repository): void
    {
        $this->repositoryGuard->ensureIsRepository($repository);
    }

    public function provideDataForEnsureIsRepository(): Iterator
    {
        yield ['.git'];
        yield ['git@github.com:Symplify/Symplify.git'];
        yield ['https://github.com/Symplify/Symplify.git'];
    }

    public function testInvalid(): Iterator
    {
        $this->expectException(InvalidRepositoryFormatException::class);

        $this->repositoryGuard->ensureIsRepository('http://github.com/Symplify/Symplify');
    }
}
