# Coding Standard

[![Build Status](https://img.shields.io/travis/Symplify/CodingStandard/master.svg?style=flat-square)](https://travis-ci.org/Symplify/CodingStandard)
[![Downloads](https://img.shields.io/packagist/dt/symplify/coding-standard.svg?style=flat-square)](https://packagist.org/packages/symplify/coding-standard)
[![Subscribe](https://img.shields.io/badge/subscribe-to--releases-green.svg?style=flat-square)](https://libraries.io/packagist/symplify%2Fcoding-standard)

Set of PHP_CodeSniffer Sniffs and PHP-CS-Fixer Fixers used by Symplify projects.

**They run best with [EasyCodingStandard](https://github.com/Symplify/EasyCodingStandard)**.

## Install

```bash
composer require symplify/coding-standard --dev
```

## Rules Overview

- Rules with :wrench: are configurable.

### Indexed PHP arrays should have 1 item per line

- class: [`Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer`](src/Fixer/ArrayNotation/StandaloneLineInMultilineArrayFixer.php)

```diff
-$friends = [1 => 'Peter', 2 => 'Paul'];
+$friends = [
+    1 => 'Peter',
+    2 => 'Paul'
+];
```

### There should not be empty PHPDoc blocks

Just like `PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer`, but this one removes all doc block lines.

- class: [`Symplify\CodingStandard\Fixer\Commenting\RemoveEmptyDocBlockFixer`](src/Fixer/Commenting/RemoveEmptyDocBlockFixer.php)

```diff
-/**
- */
 public function someMethod()
 {
 }
```

### Block comment should only contain useful information about types

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDocBlockFixer`](src/Fixer/Commenting/RemoveUselessDocBlockFixer.php)

```diff
 /**
- * @param int $value
- * @param $anotherValue
- * @param SomeType $someService
- * @return array
  */
 public function setCount(int $value, $anotherValue, SomeType $someService): array
 {
 }
```

This checker keeps 'mixed' and 'object' and other types by default. But if you need, you can **configure it**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDocBlockFixer:
        useless_types: ['mixed', 'object'] # [] by default
```

### Block comment should not have 2 empty lines in a row

- class: [`Symplify\CodingStandard\Fixer\Commenting\RemoveSuperfluousDocBlockWhitespaceFixer`](src/Fixer/Commenting/RemoveSuperfluousDocBlockWhitespaceFixer.php)

```diff
 /**
  * @param int $value
  *
- *
  * @return array
  */
 public function setCount($value)
 {
 }
```

### Include/Require should be followed by absolute path

- class: [`Symplify\CodingStandard\Fixer\ControlStructure\RequireFollowedByAbsolutePathFixer`](src/Fixer/ControlStructure/RequireFollowedByAbsolutePathFixer.php)

```diff
-require 'vendor/autoload.php';
+require __DIR__.'/vendor/autoload.php';
```

### Types should not be referenced via a fully/partially qualified name, but via a use statement

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Import\ImportNamespacedNameFixer`](src/Fixer/Import/ImportNamespacedNameFixer.php)

```diff
 namespace SomeNamespace;

+use AnotherNamespace\AnotherType;

 class SomeClass
 {
     public function someMethod()
     {
-        return new \AnotherNamespace\AnotherType;
+        return new AnotherType;
     }
 }
```

This checker imports single name classes like `\Twig_Extension` or `\SplFileInfo` by default. But if you need, you can **configure it**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Import\ImportNamespacedNameFixer:
        allow_single_names: true # false by default
```

You can also configure to check `/** @var Namespaced\DocBlocks */` as well:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Import\ImportNamespacedNameFixer:
        include_doc_blocks: true # false by default
```

And what about duplicate class name? They are uniquized by vendor name:

```php
<?php declare(strict_types=1);

namespace SomeNamespace;

use Nette\Utils\Finder as NetteFinder;
use Symfony\Finder\Finder;

class SomeClass
{
    public function create(NetteFinder $someClass)
    {
        return new Finder;
    }
}
```

### Parameters, arguments and array items should be on the same/standalone line to fit line length

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\LineLength\BreakArrayListFixer`](src/Fixer/LineLength/BreakArrayListFixer.php)
- class: [`Symplify\CodingStandard\Fixer\LineLength\BreakMethodCallsFixer`](src/Fixer/LineLength/BreakMethodCallsFixer.php)
- class: [`Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer`](src/Fixer/LineLength/LineLengthFixer.php)

```diff
 class SomeClass
 {
-    public function someMethod(SuperLongArguments $superLongArguments, AnotherLongArguments $anotherLongArguments, $oneMore)
+    public function someMethod(
+        SuperLongArguments $superLongArguments,
+        AnotherLongArguments $anotherLongArguments,
+        $oneMore
+    )
     {
     }

-    public function someOtherMethod(
-        ShortArgument $shortArgument,
-        $oneMore
-    ) {
+    public function someOtherMethod(ShortArgument $shortArgument, $oneMore) {
     }
 }
```

- Is 120 characters too long for you?
- Do you want to break longs lines but not inline short lines or vice versa?

**Change it**:

```yaml
# easy-coding-standard.yml
parameters:
    max_line_length: 100 # default: 120
    break_long_lines: true # default: true
    inline_short_lines: false # default: true
```

### Magic PHP methods (`__*()`) should respect their casing form

- class: [`Symplify\CodingStandard\Fixer\Naming\MagicMethodsNamingFixer`](src/Fixer/Naming/MagicMethodsNamingFixer.php)

```diff
 class SomeClass
 {
-    public function __CONSTRUCT()
+    public function __construct()
     {
     }
 }
```

### Property name should match its type, if possible

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Naming\PropertyNameMatchingTypeFixer`](src/Fixer/Naming/PropertyNameMatchingTypeFixer.php)

```diff
-public function __construct(EntityManagerInterface $eventManager)
+public function __construct(EntityManagerInterface $entityManager)
 {
-    $this->eventManager = $eventManager;
+    $this->entityManager = $entityManager;
 }
```

This checker ignores few **system classes like `std*` or `Spl*` by default**. In case want to skip more classes, you can **configure it**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Naming\PropertyNameMatchingTypeFixer:
        extra_skipped_classes:
            - 'MyApp*' # accepts anything like fnmatch
```

### `::class` references should be used over string for classes and interfaces

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Php\ClassStringToClassConstantFixer`](src/Fixer/Php/ClassStringToClassConstantFixer.php)

```diff
-$className = 'DateTime';
+$className = DateTime::class;
```

This checker takes **only existing classes by default**. In case want to check another code not loaded by local composer, you can **configure it**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Php\ClassStringToClassConstantFixer:
        class_must_exist: false # true by default
```

### Array property should have default value, to prevent undefined array issues

- class: [`Symplify\CodingStandard\Fixer\Property\ArrayPropertyDefaultValueFixer`](src/Fixer/Property/ArrayPropertyDefaultValueFixer.php)

```diff
 class SomeClass
 {
     /**
      * @var string[]
      */
-    public $apples;
+    public $apples = [];

     public function run()
     {
         foreach ($this->apples as $mac) {
             // ...
         }
     }
 }
```

### Strict type declaration has to be followed by empty line

- class: [`Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer`](src/Fixer/Strict/BlankLineAfterStrictTypesFixer.php)

```diff
 <?php declare(strict_types=1);
+
 namespace SomeNamespace;
```

### Non-abstract class that implements interface should be final

*Except for Doctrine entities, they cannot be final.*

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Solid\FinalInterfaceFixer`](src/Fixer/Solid/FinalInterfaceFixer.php)

```diff
-class SomeClass implements SomeInterface
+final class SomeClass implements SomeInterface
 {
 }
```

In case want check this only for specific interfaces, you can **configure them**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Solid\FinalInterfaceFixer:
        onlyInterfaces:
            - 'Symfony\Component\EventDispatcher\EventSubscriberInterface'
            - 'Nette\Application\IPresenter'
```

### Block comment should be used instead of one liner

- class: [`Symplify\CodingStandard\Fixer\Commenting\BlockPropertyCommentFixer`](src/Fixer/Commenting/BlockPropertyCommentFixer.php)

```diff
 class SomeClass
 {
-    /** @var int */
+    /**
+     * @var int
+     */
     public $count;
 }
```

### Use explicit and informative exception names over generic ones

- class: [`Symplify\CodingStandard\Sniffs\Architecture\ExplicitExceptionSniff`](src/Sniffs/Architecture/ExplicitExceptionSniff.php)

:x:

```php
throw new RuntimeException('...');
```

:+1:

```php
throw new FileNotFoundException('...');
```

### Use explicit return values over magic "&$variable" reference

- class: [`Symplify\CodingStandard\Sniffs\CleanCode\ForbiddenReferenceSniff`](src/Sniffs/CleanCode/ForbiddenReferenceSniff.php)

:x:

```php
function someFunction(&$var)
{
    $var + 1;
}
```

:+1:

```php
function someFunction($var)
{
    return $var + 1;
}
```

### Use services and constructor injection over static method

- class: [`Symplify\CodingStandard\Sniffs\CleanCode\ForbiddenStaticFunctionSniff`](src/Sniffs/CleanCode/ForbiddenStaticFunctionSniff.php)

:x:

```php
class SomeClass
{
    public static function someFunction()
    {
    }
}
```

:+1:

```php
class SomeClass
{
    public function someFunction()
    {
    }
}
```

### Constant should have docblock comment

- class: [`Symplify\CodingStandard\Sniffs\Commenting\VarConstantCommentSniff`](src/Sniffs/Commenting/VarConstantCommentSniff.php)

```php
class SomeClass
{
    private const EMPATH_LEVEL = 55;
}
```

:+1:

```php
class SomeClass
{
    /**
     * @var int
     */
    private const EMPATH_LEVEL = 55;
}
```

### There should not be comments with valid code

- class: [`Symplify\CodingStandard\Sniffs\Debug\CommentedOutCodeSniff`](src/Sniffs/Debug/CommentedOutCodeSniff.php)

:x:

```php
// $file = new File;
// $directory = new Diretory([$file]);
```

### Debug functions should not be left in the code

- class: [`Symplify\CodingStandard\Sniffs\Debug\DebugFunctionCallSniff`](src/Sniffs/Debug/DebugFunctionCallSniff.php)

:x:

```php
dump($value);
```

### Use service and constructor injection rather than instantiation with new

- :wrench:
- class: [`Symplify\CodingStandard\Sniffs\DependencyInjection\NoClassInstantiationSniff`](src/Sniffs/DependencyInjection/NoClassInstantiationSniff.php)

:x:

```php
class SomeController
{
   public function renderEdit(array $data)
   {
        $database = new Database;
        $database->save($data);
   }
}
```

:+1:

```php
class SomeController
{
   public function renderEdit(array $data)
   {
        $this->database->save($data);
   }
}
```

This checkers ignores by default some classes, see `$allowedClasses` property.

In case want to exclude more classes, you can **configure it** with class or pattern using [`fnmatch`](http://php.net/manual/en/function.fnmatch.php):

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\DependencyInjection\NoClassInstantiationSniff:
        extraAllowedClasses:
            - 'PhpParser\Node\*'
```

Doctrine entities are skipped as well. You can disable that by:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\DependencyInjection\NoClassInstantiationSniff:
        includeEntities: true
```

### Abstract class should have prefix "Abstract"

- class: [`Symplify\CodingStandard\Sniffs\Naming\AbstractClassNameSniff`](src/Sniffs/Naming/AbstractClassNameSniff.php)

```diff
-abstract class SomeClass
+abstract class AbstractSomeClass
 {
 }
```

### Class should have suffix by parent class/interface

- :wrench:
- class: [`Symplify\CodingStandard\Fixer\Naming\ClassNameSuffixByParentFixer`](src/Fixer/Naming/ClassNameSuffixByParentFixer.php)

```diff
-class Some extends Command
+class SomeCommand extends Command
 {
 }
```

This checker check few names by default. But if you need, you can **configure it**:

```yaml
# easy-coding-standard.yml
services:
    Symplify\CodingStandard\Fixer\Naming\ClassNameSuffixByParentFixer:
        parent_types_to_suffixes:
            # defaults
            '*Command': 'Command'
            '*Controller': 'Controller'
            '*Repository': 'Repository'
            '*Presenter': 'Presenter'
            '*Request': 'Request'
            '*EventSubscriber': 'EventSubscriber'
```

It also covers `Interface` suffix as well, e.g `EventSubscriber` checks for `EventSubscriberInterface` as well.

### Exception should have suffix "Exception"

- class: [`Symplify\CodingStandard\Fixer\Naming\ExceptionNameSniff`](src/Fixer/Naming/ExceptionNameFixer.php)

```diff
-class SomeClass extends Exception
+class SomeClassException extends Exception
 {
 }
```

### Interface should have suffix "Interface"

- class: [`Symplify\CodingStandard\Sniffs\Naming\InterfaceNameSniff`](src/Sniffs/Naming/InterfaceNameSniff.php)

```diff
-interface Some
+interface SomeInterface
 {
 }
```

### Trait should have suffix "Trait"

- class: [`Symplify\CodingStandard\Sniffs\Naming\TraitNameSniff`](src/Sniffs/Naming/TraitNameSniff.php)

```diff
-trait Some
+trait SomeTrait
 {
 }
```

## Brave Checkers

### Possible Unused Public Method

- class: [`Symplify\CodingStandard\Sniffs\DeadCode\UnusedPublicMethodSniff`](src/Sniffs/DeadCode/UnusedPublicMethodSniff.php)

- **Requires ECS due *double run* feature**.

:x:

```php
class SomeClass
{
    public function usedMethod()
    {
    }

    public function unusedMethod()
    {
    }
}

$someObject = new SomeClass;
$someObject->usedMethod();
```

:+1:

```php
class SomeClass
{
    public function usedMethod()
    {
    }
}

$someObject = new SomeClass;
$someObject->usedMethod();
```

## Contributing

Open an [issue](https://github.com/Symplify/Symplify/issues) or send a [pull-request](https://github.com/Symplify/Symplify/pulls) to main repository.
