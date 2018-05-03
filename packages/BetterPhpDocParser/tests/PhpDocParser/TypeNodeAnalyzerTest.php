<?php declare(strict_types=1);

namespace Symplify\BetterPhpDocParser\Tests\PhpDocParser;

use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Symplify\BetterPhpDocParser\PhpDocParser\TypeNodeAnalyzer;
use Symplify\BetterPhpDocParser\Tests\AbstractContainerAwareTestCase;

final class TypeNodeAnalyzerTest extends AbstractContainerAwareTestCase
{
    /**
     * @var TypeNodeAnalyzer
     */
    private $typeNodeAnalyzer;

    protected function setUp(): void
    {
        $this->typeNodeAnalyzer = $this->container->get(TypeNodeAnalyzer::class);
    }

    public function testContainsArrayType(): void
    {
        $arrayTypeNode = new ArrayTypeNode(new IdentifierTypeNode('int'));

        $this->assertFalse($this->typeNodeAnalyzer->containsArrayType(new IdentifierTypeNode('int')));
        $this->assertTrue($this->typeNodeAnalyzer->containsArrayType($arrayTypeNode));
        $this->assertTrue($this->typeNodeAnalyzer->containsArrayType(new UnionTypeNode([$arrayTypeNode])));
    }

    public function testIsIntersectionAndNotNullable(): void
    {
        $intersectionTypeNode = new IntersectionTypeNode([new IdentifierTypeNode('int')]);
        $nullableTypeNode = new IntersectionTypeNode([new NullableTypeNode(new IdentifierTypeNode('int'))]);

        $this->assertTrue($this->typeNodeAnalyzer->isIntersectionAndNotNullable($intersectionTypeNode));
        $this->assertFalse($this->typeNodeAnalyzer->isIntersectionAndNotNullable($nullableTypeNode));
    }
}
