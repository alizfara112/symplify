<?php declare(strict_types=1);

namespace Symplify\LatteToTwigConverter\Tests;

use Iterator;
use PHPUnit\Framework\TestCase;
use Symplify\LatteToTwigConverter\LatteToTwigConverter;

final class LatteToTwigConverterTest extends AbstractContainerAwareTestCase
{
    /**
     * @var LatteToTwigConverter
     */
    private $latteToTwigConverter;

    protected function setUp(): void
    {
        $this->latteToTwigConverter = $this->container->get(LatteToTwigConverter::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $latteFile, string $expectedTwigFile): void
    {
        $convertedFile = $this->latteToTwigConverter->convertFile($latteFile);
        $this->assertStringEqualsFile($expectedTwigFile, $convertedFile);
    }

    public function provideData(): Iterator
    {
        yield [
            __DIR__ . '/LatteToTwigConverterSource/variables.latte',
            __DIR__ . '/LatteToTwigConverterSource/expected-variables.twig',
        ];

        yield [
            __DIR__ . '/LatteToTwigConverterSource/block-file.latte',
            __DIR__ . '/LatteToTwigConverterSource/expected-block-file.twig',
        ];

        yield [
            __DIR__ . '/LatteToTwigConverterSource/filter.latte',
            __DIR__ . '/LatteToTwigConverterSource/expected-filter.twig',
        ];

        yield [
            __DIR__ . '/LatteToTwigConverterSource/loops.latte',
            __DIR__ . '/LatteToTwigConverterSource/expected-loops.twig',
        ];

        yield [
            __DIR__ . '/LatteToTwigConverterSource/conditions.latte',
            __DIR__ . '/LatteToTwigConverterSource/expected-conditions.twig',
        ];
    }
}
