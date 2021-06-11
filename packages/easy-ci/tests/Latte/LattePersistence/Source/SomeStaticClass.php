<?php

declare(strict_types=1);

namespace Symplify\EasyCI\Tests\Latte\LattePersistence\Source;

final class SomeStaticClass
{
    public static function plus(int $number, int $anotherNumber): int
    {
        return $number + $anotherNumber;
    }
}
