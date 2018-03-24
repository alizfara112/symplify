<?php declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Fixer\Commenting\RemoveUselessDocBlockFixer;

use Symplify\EasyCodingStandardTester\Testing\AbstractCheckerTestCase;

/**
 * @see \Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDocBlockFixer
 */
final class RemoveUselessDocBlockFixerTest extends AbstractCheckerTestCase
{
    /**
     * @dataProvider provideCorrectCases()
     */
    public function testCorrectCases(string $file): void
    {
        $this->doTestCorrectFile($file);
    }

    /**
     * @return string[][]
     */
    public function provideCorrectCases(): array
    {
        return [
            [__DIR__ . '/correct/correct.php.inc'],
            [__DIR__ . '/correct/correct2.php.inc'],
            [__DIR__ . '/correct/correct3.php.inc'],
            [__DIR__ . '/correct/correct4.php.inc'],
            [__DIR__ . '/correct/correct5.php.inc'],
            [__DIR__ . '/correct/correct6.php.inc'],
            [__DIR__ . '/correct/correct7.php.inc'],
            [__DIR__ . '/correct/correct8.php.inc'],
            [__DIR__ . '/correct/correct9.php.inc'],
            [__DIR__ . '/correct/correct10.php.inc'],
            [__DIR__ . '/correct/correct11.php.inc'],
            [__DIR__ . '/correct/correct12.php.inc'],
            [__DIR__ . '/correct/correct13.php.inc'],
            [__DIR__ . '/correct/correct14.php.inc'],
            [__DIR__ . '/correct/correct15.php.inc'],
        ];
    }

    /**
     * @dataProvider provideWrongToFixedCases()
     */
    public function testFix(string $wrongFile, string $fixedFile): void
    {
        $this->doTestWrongToFixedFile($wrongFile, $fixedFile);
    }

    /**
     * @return string[][]
     */
    public function provideWrongToFixedCases(): array
    {
        return [
            [__DIR__ . '/wrong/wrong.php.inc', __DIR__ . '/fixed/fixed.php.inc'],
            [__DIR__ . '/wrong/wrong2.php.inc', __DIR__ . '/fixed/fixed2.php.inc'],
            [__DIR__ . '/wrong/wrong3.php.inc', __DIR__ . '/fixed/fixed3.php.inc'],
            [__DIR__ . '/wrong/wrong4.php.inc', __DIR__ . '/fixed/fixed4.php.inc'],
            [__DIR__ . '/wrong/wrong5.php.inc', __DIR__ . '/fixed/fixed5.php.inc'],
            [__DIR__ . '/wrong/wrong6.php.inc', __DIR__ . '/fixed/fixed6.php.inc'],
            [__DIR__ . '/wrong/wrong7.php.inc', __DIR__ . '/fixed/fixed7.php.inc'],
            [__DIR__ . '/wrong/wrong8.php.inc', __DIR__ . '/fixed/fixed8.php.inc'],
            [__DIR__ . '/wrong/wrong9.php.inc', __DIR__ . '/fixed/fixed9.php.inc'],
            [__DIR__ . '/wrong/wrong10.php.inc', __DIR__ . '/fixed/fixed10.php.inc'],
            [__DIR__ . '/wrong/wrong11.php.inc', __DIR__ . '/fixed/fixed11.php.inc'],
            [__DIR__ . '/wrong/wrong12.php.inc', __DIR__ . '/fixed/fixed12.php.inc'],
            [__DIR__ . '/wrong/wrong15.php.inc', __DIR__ . '/fixed/fixed15.php.inc'],
            [__DIR__ . '/wrong/wrong16.php.inc', __DIR__ . '/fixed/fixed16.php.inc'],
            [__DIR__ . '/wrong/wrong17.php.inc', __DIR__ . '/fixed/fixed17.php.inc'],
            [__DIR__ . '/wrong/wrong18.php.inc', __DIR__ . '/fixed/fixed18.php.inc'],
        ];
    }

    protected function provideConfig(): string
    {
        return __DIR__ . '/config.yml';
    }
}
