# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

PRs and issues are linked, so you can find more about it. Thanks to [ChangelogLinker](https://github.com/Symplify/ChangelogLinker).

## [v4.1.0] - 2018-04-24

### Added

- [#768] Add `Symplify\CodingStandard\Sniffs\ControlStructure\SprintfOverContactSniff`

### Changed

#### BetterReflectionDocBlock

- [#783], [#786], [#788], [#789] Migrate from `phpdocumentor/reflection-docblock` to `phpstan/phpdoc-parser` with more advanced API, keeping format preserving printer

#### CodingStandard

- [#790] Add inline support to `LineLenghtFixer` for arrays to be consistent with other structures - function, method arguments, new arguments etc. already do

- [#766] Make class-renaming checkers inform only
    - `Symplify\CodingStandard\Sniffs\Naming\AbstractClassNameSniff`
    - `Symplify\CodingStandard\Sniffs\Naming\InterfaceNameSniff`
    - `Symplify\CodingStandard\Sniffs\Naming\TraitNameSniff`

#### EasyCodingStandard

- [#764] Remove `sqlite` hidden dependency by using `symfony/cache`

- [#776] Print fixable messages as warnings, thanks [@OndraM]

### Fixed

- [#770] **CodingStandard** Fix `RemoveUselessDocBlockFixer` for useful `@return` tag description

#### EasyCodingStandard

- [#785] Fix skipper - remove global code skip from unused

- [#759] Fix cache invalidation for files with error or change, closes [#759]

- [#758] Fix missed unreported skips, closes [#750]

- [#757] Fix `@var` invalid types by adding `TolerantVar`

- [#771] Fix false report of unused errors while using `parameters > skip: > Sniff.Code: ~`

- [#763] Fix `CheckerServiceParametersShifter` for `null` value

#### BetterReflectionDocBlock

- [#769] Fix `TolerantVar` consistency

### Deprecated

#### CodingStandard

- [#767] Deprecate `Symplify\CodingStandard\Fixer\Naming\ExceptionNameFixer` in favor of `Symplify\CodingStandard\Fixer\Naming\ClassNameSuffixByParentFixer` that does the same job and is extra configurable

#### BetterReflectionDocBlock

- Deprecate after migration to [phpstan/phpdoc-parser](https://github.com/phpstan/phpdoc-parser) in [#783], [#786], [#788], [#789]

## [v4.0.0] - 2018-04-02

Biggest change of this release is moving from mixture of Yaml and Neon format in `*.neon` files to Yaml format in `*.yaml` files. That will make Symplify packages more world-friendly and standard rather than Czech-only Neon format. See [#651](https://github.com/Symplify/Symplify/pull/651) about more reasoning behind this.

This change was finished in [Statie](https://github.com/Symplify/Statie) and [EasyCodingStandard](https://github.com/Symplify/EasyCodingStandard), where mostly requested.

### Added

- [#589] Add version printing on `-V` option in Console Applications, thanks to [@ostrolucky]

#### PackageBuilder

- [#755] Add `Symplify\PackageBuilder\Yaml\AbstractParameterMergingYamlFileLoader` for standalone use

- [#732] Add support for `Error` rendering to `Symplify\PackageBuilder\Console\ThrowableRenderer` (former `ExceptionRenderer`)

- [#720] Add `Symplify\PackageBuilder\Console\ExceptionRenderer` to render exception nicely Console Applications but anywhere outside it; follow up to [#715] and [#702]

- [#713] Add shortcut support for config `-c` in `ConfigFileFinder` and for level `-l` in `LevelFileFinder`

- [#680](https://github.com/Symplify/Symplify/pull/680/files#diff-412c71ea9d7b9fa9322e1cf23e39a1e7) Add `PublicForTestsCompilerPass` to remove `public: true` in configs and still allow `get()` use in tests

- [#645] Add `AutowireSinglyImplementedCompilerPass` to prevent redundant singly-interface binding

- [#612] Add `CommandNaming` to get command name from the class name

#### CodingStandard

- [#749] Add `Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer`, based on previous PRs: [#747], [#743], [#585], [#591], with configuration:

    ```yaml
    # easy-coding-standard.yml
    services:
        Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer:
            max_line_length: 100 # default: 120
            break_long_lines: true # default: true
            inline_short_lines: false # default: true
    ```

- [#722] Add `ForbiddenStaticFunctionSniff`

- [#707], [#709] Upgrade to [PHP CS Fixer 2.11](https://github.com/FriendsOfPHP/PHP-CS-Fixer/tree/v2.11.0)

- [#692] Add `ForbiddenReferenceSniff` to check all `&$var` references

- [#690] Make `RemoveUselessDocBlockFixer` cover functions as well

- [#633] Add `ClassNameSuffixByParentFixer`, closes [#607]

#### EasyCodingStandard

- [#706] Add [PSR-12 set](https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md), use in CLI `--level psr12` or import `psr12.yml`

- [#741] Add support for `%vendor_dir%`, `%current_working_dir%` variables in `imports` section of configs to allow simpler loading [lmc-eu/php-coding-standard#6](https://github.com/lmc-eu/php-coding-standard/pull/6)

    ```yaml
    # lmc-coding-standard.yml
    imports:
        - { resource: '%vendor_dir%/symplify/easy-coding-standard/config/psr2.yml' }
        # or
        - { resource: '%current_working_dir%/vendor/symplify/easy-coding-standard/config/psr2.yml' }
    ```

- [#705] Add `-c` shortcut for `--config` CLI option, thanks to [@OndraM]

- [#698] Autodiscover `*.yaml` suffix as well

- [#656] Add configurable cache directory for changed files, closes [#650], thanks to [@marmichalski]
    ```yml
    # easy-coding-standard.yml
    parameters:
        cache_directory: .ecs_cache # defaults to sys_get_temp_dir() . '/_easy_coding_standard'
    ```

- [#583][#584] Add `exclude_files` option to exclude files, with `fnmatch` support:
   ```yml
   # easy-coding-standard.yml
   parameters:
       exclude_files:
           - 'lib/PhpParser/Parser/Php5.php'
           - 'lib/PhpParser/Parser/Php7.php'
           # new
           - '*/lib/PhpParser/Parser/Php*.php'
   ```

### Changed

- [#744] **CodingStandard**, **TokenRunner** Abstract line length logic from all fixers to `LineLengthTransformer`

- [#722] **TokenRunner** Move form `static` to service and constructor injection

- [#693] **CodingStandard** Move checkers from static to services, follow up to [#680]

- [#680] **BetterReflectionDocBlock** First steps to migration from [phpDocumentor/ReflectionDocBlock](https://github.com/phpDocumentor/ReflectionDocBlock) to [phpstan/phpdoc-parser](https://github.com/phpstan/phpdoc-parser)

- [#654] **Statie** Move from Yaml + Neon mixture to Yaml, similar to [#651]
    - [How to migrate from `*.neon` to `*.yml`](https://www.tomasvotruba.cz/blog/2018/03/12/neon-vs-yaml-and-how-to-migrate-between-them/#how-to-migrate-from-neon-to-yaml)?

- [#721] Prefer `Input` and `Output` instances injected via constructor in used Console Applications

#### PackageBuilder

- [#742] Decouple `Symplify\PackageBuilder\Yaml\ParameterInImportResolver` class for standalone use

- [#730] Renamed class `Symplify\PackageBuilder\Console\ExceptoinRenderer` to `Symplify\PackageBuilder\Console\ThrowableRenderer`

- [#713] Renamed class `Symplify\PackageBuilder\Configuration\ConfigFilePathHelper` to `Symplify\PackageBuilder\Configuration\LevelConfigShortcutFinder`

- [#713] Renamed class `Symplify\PackageBuilder\Configuration\ConfigFileFinder` to `Symplify\PackageBuilder\Configuration\LevelFileFinder`

- [#713] Renamed method `Symplify\PackageBuilder\Configuration\LevelFileFinder::resolveLevel()` to `Symplify\PackageBuilder\Configuration\LevelFileFinder::detectFromInputAndDirectory()`

#### EasyCodingStandard

- [#717] Make error report more verbose, closes [#701]

- [#712] Move from `Symplify\EasyCodingStandard\Testing\AbstractContainerAwareCheckerTestCase` to new `Symplify\EasyCodingStandardTester\Testing\AbstractCheckerTestCase`

- [#700] Rename deprecated Fixers to their new equivalents, thanks [@OndraM]

- [#680] Move from statics in checkers to autowired DI

- [#660] Move from `checkers` to `services`, follow up to [#651]
    ```diff
    # easy-coding-standard.yml
    -    checkers:
    +    services:
             Symplify\CodingStandard\Fixer\Import\ImportNamespacedNameFixer:
                 include_doc_blocks: true

    # this is needed to respect yaml format
    -        - SlamCsFixer\FinalInternalClassFixer:
    +        SlamCsFixer\FinalInternalClassFixer: ~
    ```

- [#661] Merge `parameters > skip_codes` to `parameters > skip` section
    ```diff
     # easy-coding-standard.yml
     parameters:
         skip:
             PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff:
                - 'packages/CodingStandard/src/Fixer/ClassNotation/LastPropertyAndFirstMethodSeparationFixer.php'

    -    skip_codes:
             SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff.UselessDocComment:
                 - '*packages*'
    ```

- [#651] Move from mixture custom neon + Symfony service DI to Yaml
    - [How to migrate from `*.neon` to `*.yml`](https://www.tomasvotruba.cz/blog/2018/03/12/neon-vs-yaml-and-how-to-migrate-between-them/#how-to-migrate-from-neon-to-yaml)?

### Fixed

#### EasyCodingStandard

- [#727][#740] Fix merging of `parameters` from imported configs, ref [symfony/symfony/#26713](https://github.com/symfony/symfony/issues/26713)

- [#729] Detect changes properly also in `*.yaml` files, thanks [@OndraM]

- [#640] Fix pre-mature adding file to cache, fixes [#637]

#### Statie

- [#595] Fix race condition for element sorting with configuration

- [59bdfc] Fix non-root `index.html` route, fixes [#638]

#### CodingStandard

- [#693] Fix `UnusedPublicMethodSniff` for static methods

- [#606] Fix few `RemoveUselessDocBlockFixer` cases

- [#598] Fix `PropertyNameMatchingTypeFixer` for self cases, fixes [#597]

#### BetterReflectionDocBlock

- [#599] Fix respecting spaces of inner tag

- [#603] Fix union-types pre-slash clean + some more for `RemoveUselessDocBlockFixer`

- [1fcc92] Fix variadic detection

- [caf08e] Fix escaping and variadic param resolver

### Removed

#### Statie

- [#647] Removed deprecated `vendor/bin/statie push-to-github` command, use [Github pages on Travis](https://www.statie.org/docs/github-pages/#allow-travis-to-make-changes) instead

- [#647] Removed deprecated `parameters > github_repository_slug` option, use `github_repository_source_directory` instead

    ```diff
     # statie.yml
     parameters:
    -    # <user>/<repository>
    -    github_repository_slug: "pehapkari/pehapkari.cz"
    +    # https://github.com/<user>/<repository>/tree/master/<source>, where <source> is name of directory with Statie content
    +    github_repository_source_directory: "https://github.com/pehapkari/pehapkari.cz/tree/master/source"
    ```

- [#647] Removed deprecated `statie.neon` note, use `statie.yml` instead

#### PackageBuilder

- [#720] Removed `Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory`, that was only for exception rendering; use `Symplify\PackageBuilder\Console\ExceptionRenderer` instead

- [#651] Removed `Symplify\PackageBuilder\Neon\Loader\NeonLoader` and `Symplify\PackageBuilder\Neon\NeonLoaderAwareKernelTrait`, that attempted to put Neon into Symfony Kernel, very poorly though; Yaml and Symfony DI is now used insteads

#### EasyCodingStandard

- [#712] Removed `Symplify\EasyCodingStandard\Testing\AbstractContainerAwareCheckerTestCase`, use `Symplify\EasyCodingStandardTester\Testing\AbstractCheckerTestCase` instead

- [#647] Removed deprecated bin files: `vendor/bin/easy-coding-standard` and `vendor/bin/easy-coding-standard.php`; use `vendor/bin/ecs` instead

- [#693] Removed `AbstractSimpleFixerTestCase` in favor of more general and advanced `AbstractContainerAwareCheckerTestCase`

#### CodingStandard

- [#708] Removed `AnnotateMagicContainerGetterFixer`, use awesome [Symfony Plugin](https://plugins.jetbrains.com/plugin/7219-symfony-plugin) instead

- [#688] Removed `DynamicPropertySniff`, use `PHPStan\Rules\Properties\AccessPropertiesRule`  PHPStan with [`--level 0`](https://github.com/phpstan/phpstan/blob/3485d8ce8c64a6becf6cc60f268d051af6ff7ceb/conf/config.level0.neon#L28) instead

- [#647] Removed deprecated `LastPropertyAndFirstMethodSeparationFixer`, see [#594], use [`PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/b7cc8727c7faa8ebe7cc4220daaaabe29751bc5c/src/Fixer/ClassNotation/ClassAttributesSeparationFixer.php) instead; extends it if you need different space count

- [#647] Removed deprecated `Symplify\CodingStandard\Fixer\Strict\InArrayStrictFixer`, use [`PhpCsFixer\Fixer\Strict\StrictParamFixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/b7cc8727c7faa8ebe7cc4220daaaabe29751bc5c/src/Fixer/Strict/StrictParamFixer.php) instead, that does the same job

- [#749] Removed `BreakMethodArgumentsFixer`, `BreakArrayListFixer`, use `Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer` instead

## [v3.2.0] - 2018-01-13

### Added

- [#570] **EasyCodingStandard** Add reporting for duplicated checkers

- [#577] **Statie** Add customizable `ObjectSorter` for [Generators](https://www.statie.org/docs/generators/) as `object_sorter` option in Generator configuration

    ```yaml
    # statie.yml
    parameters:
        generators:
            posts:
                # ...

                # Symplify\Statie\Generator\FileNameObjectSorter is used by default,
                # it sorts files newer to older posts work by default
                object_sorter: 'Website\Statie\Generator\DateObjectSorter'
    ```

    The sorter needs to implement `Symplify\Statie\Generator\Contract\ObjectSorterInterface` and be [loaded by composer](https://stackoverflow.com/a/25960097/1348344).

    It returns sorting function. For inspiration see [`Symplify\Statie\Generator\FileNameObjectSorter`](/packages/Statie/packages/Generator/src/FileNameObjectSorter.php) for inspiration.

### Changed

- [#576] Bump to PHP CS Fixer 2.10 + minor lock to prevent BC breaks that happen for last 4 minor versions

#### EasyCodingStandard

- [#560] Added `UnnecessaryStringConcatSniff` to `clean-code.neon` level, thanks to [@carusogabriel]
- [#560] Added `PhpdocVarWithoutNameFixer` to `docblock.neon` level, thanks to [@carusogabriel]

### Fixed

#### Statie

- [#574] Fix path in `FileFinder` for Windows, thanks to [@tomasfejfar]
- [#562] Fix `preg_quote()` escaping, thanks to [@tomasfejfar]

### Deprecated

- [#559] **Statie** Deprecated `push-to-github` command; use [Github Deploy](https://www.statie.org/docs/github-pages/) instead

- [#558] **CodingStandard** Deprecated `Symplify\CodingStandard\Fixer\Strict\InArrayStrictFixer`; use `PhpCsFixer\Fixer\Strict\StrictParamFixer` instead; thanks to [@carusogabriel]

## [v3.1.0] - 2018-01-02

### Added

- [#505] Added `CHANGELOG.md`

### Changed

- [#508] **CodingStandard** `RemoveUselessDocBlockFixer` is now configurable to accept types to remove with your own preferences

    ```yaml
    # easy-coding-standard.neon
    checkers:
        Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDocBlockFixer:
            useless_types: ['mixed']
            # "[]" is default
    ```

- [3fce4e] **EasyCodingStandard** drop `LineLimitSebastianBergmanDiffer` over `PhpCsFixer\Differ\UnifiedDiffer`

## [v3.0.0] - 2017-12-09

### Added

- [#473] bump to Symfony 4

- [#437] **TokenRunner** improved `AbstractSimpleFixerTestCase` with clearly named methods

#### Statie

- [#475] Added support for generators
    ```yaml
    parameters:
        generators:
            # key name, it's nice to have for more informative error reports
            posts:
                # name of variable inside single such item
                variable: post
                # name of variable that contains all items
                varbiale_global: posts
                # directory, where to look for them
                path: '_posts'
                # which layout to use
                layout: '_layouts/@post.latte'
                # and url prefix, e.g. /blog/some-post.md
                route_prefix: 'blog'
                # an object that will wrap it's logic, you can add helper methods into it and use it in templates
                object: 'Symplify\Statie\Renderable\File\PostFile'
    ```
- [9b154d] Added `-vvv` CLI option for debug output

#### EasyCodingStandard

- [#481] Add warning as error support, to make useful already existing Sniffs, closes [#477]

- [#473] Added `LineLimitSebastianBergmannDiffer` for nicer and compact diff outputs

- [#443] Added smaller common configs for better `--level` usage

- [#447] Allow `-vvv` for ProgressBar + **27 % speed improvement**

- [#388] Added support for ignoring particular sniff codes

- [#406] Added support for ignoring particular codes and files, Thanks to [@ostrolucky]

- [#397] Added validation to `exclude_checkers` option, Thanks to [@mzstic]

#### Coding Standard

- [#480] Added `RemoveSuperfluousDocBlockWhitespaceFixer`, which removes 2 spaces in a row in doc blocks

- [#466] Added `Symplify\CodingStandard\Sniffs\DeadCode\UnusedPublicMethodSniff`

- [#452] `ClassStringToClassConstantFixer` now covers classes with double slashes: `SomeNamespace\\SomeClass`

- [0ab538] Added `BlankLineAfterStrictTypesFixer`

- [#385] Added `RequireFollowedByAbsolutePathFixer`

- [#421] Added `ImportNamespacedNameFixer`

- [#427] Added `RemoveUselessDocBlockFixer`

#### PackageBuilder

- [#442] Added `AutoloadFinder` to find nearest `/vendor/autoload.php`

- [#442] Added `provideParameter()` and `changeParameter()` methods to `ParameterProvider`

- [#431] Added `--level` shortcut helper builder

### Changed

- [#473] **CodingStandard** use [ReflectionDocBlock](https://github.com/phpDocumentor/ReflectionDocBlock) for docblock analysis and modification

#### Statie

- [#484] Add *dry-run* option to `StatieApplication` and `BeforeRenderEvent` to improve extendability, closes [#483]

- [9a9c0e] Use `statie.yml` config based on Symfony DI over "fake" `statie.neon` to prevent confusion, closes [#487]

    ```diff
    -includes:
    +imports:
    -     - source/data/config.neon
    +     - { resource: 'source/data/config.yml' }
    ```

    And simple services:

    ```diff
     services:
    -    -
    -        class: App\TranslationProvider
    +    App\TranslationProvider: ~
    ```

- [#475] Renamed `relatedPosts` filter to `relatedItems` with general usage (not only posts, but any other own generator element)

    ```diff
    -{var $relatedPosts = ($post|relatedPosts)}
    +{var $relatedPosts = ($post|relatedItems)}
    ```

- [#399] Filter `similarPosts` renamed to `relatedPosts`, closes [#386]

#### EasyCodingStandard

- [#474] Prefer diff report for changes over table report

- [#472] Improve `FileProcessorInterface`, improve performance via `CachedFileLoader`

- [881577] Removed `-checkers` suffix to make file naming consistent

### Removed

- [#475] **Statie** removed `postRoute`, only `prefix` is now available per item in generator

- [#412] **PackageBuilder** Removed Nette related-features, make package mostly internall for Symplify

- [#404] **SymbioticController** package deprecated, closes [#402]

#### CodingStandard

- [#488] Dropped `PropertyAndConstantSeparationFixer`, use `PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer` instead

- [#476] Dropped `NoInterfaceOnAbstractClassFixer`, not useful in practise

- [#443] Dropped `FinalTestCase`, use `SlamCsFixer\FinalInternalClassFixer` instead

- [#417] Dropped `InjectToConstructorInjectionFixer`, use [@RectorPHP] instead

- [#419] Dropped `ControllerRenderMethodLimitSniff` and `InvokableControllerSniff`, as related to SymbioticController

- [#432] Dropped `NewClassSniff`, use `NewWithBracesFixer` instead

#### EasyCodingStandard

- [bc0cb0] `php54.neon` set removed

- [#430] Dropped `--fixer-set` and `--checker-set` options for `show` command, proven as not useful

## [v2.5.0] - 2017-10-08

### Added

- [#374] **CodingStandard** Added customg matching with `fnmatch()` to PropertyNameMatchingTypeFixer

### Changed

- [#365] **CodingStandard** Bumped to PHP_CodeSniffer 3.1

### Fixed

- [#379] **Statie** Fixed source path bug, Thanks to [@chemix]

#### CodingStandard

- [#370] Fixed `PropertyAndConstantSeparation` for multiple classes

- [#372] Fixed incorrect namespace resolving in `NoClassInstantiationSniff`

- [#376] Fixed nested array in `ArrayPropertyDefaultValueFixer`

- [#381] Fixed `DynamicProperySniff` miss

### Removed

- [#382] **Statie** Dropped broken and poor AMP support

## [v2.4.0] - 2017-09-20

### Added

- [#359] **Statie** Added Markdown support in `perex` in post

#### CodingStandard

- [#360] Added `--no-progress-bar` option, added `--no-error-table` option

- [#358] Added `AnnotateMagicContainerGetterFixer`

- [#356] Added `PropertyNameMatchingTypeFixer`

- [#346] Added `LastPropertyAndFirstMethodSeparationFixer`

#### EasyCodingStandard

- [#354] Added [clean-code set](https://www.tomasvotruba.cz/blog/2017/09/18/4-simple-checkers-for-coding-standard-haters-but-clean-code-lovers/)

- [430fc5] ConflictingGuard feature added, see [#333]

- [33f28a] Add new rules to `symfony-checkers`

- [#342] Add parser error reporting, Thanks [@webrouse]

### Changed

- [d350b1] Bump to `slevomat/coding-standard` 4.0

- [bf8024] Bump to `friendsofphp/php-cs-fixer` 2.6

### Fixed

- [#347] **CodingStandard** Fix comment behind constant in `PropertyAndConstantSeparationFixer`

- [#355] **EasyCodingStandard** Fix `fnmatch` support both for relative and absolute paths

## [v2.3.0] - 2017-09-06

### Added

#### CodingStandard

- [#360] Added `--no-progress-bar` option, added `--no-error-table` option

- [#338] Added `PropertyAndConstantSeparationFixer`

- [#332] Added `DynamicPropertySniff`

- [#320] Added `NoClassInstantiationSniff`

- [#311] Added `StandaloneLineInMultilineArrayFixer`

- [#283] Added `ExceptionNameFixer`

#### EasyCodingStandard

- [#330] Added performance overview per checker via `--show-performance` options

- [#305] Added `MutualCheckerExcluder`

- [#301] Added `exclude_checkers` option to config, that allows to exclude specific checkers, e.g. inherited from configs

- [#290] Added prepared sets with checkers

- [#285] Added sniff set support to `show` command via `--sniff-set` option

### Changed

- [#295] **Statie** Rework similar posts concept from semi-AI magic to manual option `related_posts` in post file

#### CodingStandard

- [#334] `ArrayPropertyDefaultValueFixer` now allows single line comments, Thanks to [@vlastavesely]

- [#314] Make `ClassStringToClassConstantFixer` configurable

#### EasyCodingStandard

- [#337] Fail table is less agressive

- [#287] Allow SourceFinder to return Nette or Symfony Finder without any extra work

### Fixed

- [#331] **CodingStandard** Fix `StandaloneLineInMultilieArray` with comments

- [#289] **EasyCodingStandard** Fix skipper for `fnmatch`

#### Statie

- [#328] Removed hardcoded path from github filter, Thanks to [@crazko]

- [#327] Fixed ability to set layout for posts

- [#325] Disable decoration when AMP disabled

## [v2.2.0] - 2017-07-26

**News for EasyCodingStandard 2.2 explained in a post: [7 new features in EasyCodingStandard 2.2](https://www.tomasvotruba.cz/blog/2017/08/07/7-new-features-in-easy-coding-standard-22)**

### Added

#### CodingStandard

- [#262] Added `ClassStringToClassConstantFixer`, convert `"SomeClass"` to `SomeClass::class`

- [#279] Added `MagicMethodsNamingFixer`, converts `__CONSTUCT()` to `__construct()`, Thanks [@SpacePossum]

#### EasyCodingStandard

- [#234] Added support for custom spaces/tabs indentation in PHP-CS-Fixer

- [#266], [#272] Added support for custom SourceProvider

- [#267] Added ready to go configs with group of PHP-CS-Fixer fixers, `psr2`, `symfony`, `php70`, `php71` etc.

    Use in CLI:

    ```bash
    vendor/bin/ecs check src --config  vendor/symplify/easy-coding-standard/config/psr2-checkers.neon
    ```

    or `easy-coding-standard.neon`

    ```yaml
    includes:
        - vendor/symplify/easy-coding-standard/config/php70-checkers.neon
        - vendor/symplify/easy-coding-standard/config/php71-checkers.neon
        - vendor/symplify/easy-coding-standard/config/psr2-checkers.neon
    ```

- [#267] Added option to `show` command, to show fixers from specific set from PHP-CS-Fixer:

    ```bash
    vendor/bin/ecs show --fixer-set Symfony
    ```

    And with configuration (parameters):

    ```bash
    vendor/bin/ecs show --fixer-set Symfony --with-config
    ```

- [#281] Added info about no checkers loaded + allow checker merging in configuration

#### PackageBuilder

- [#276] Added support for absolute path in `--config`, Thanks [@dg]

- [#225] Added `ParameterProvider` for Nette

#### Statie

- [#243], [#258], [#275] Added cache for AMP + various fixes

- [#252], [#256] Added support for Latte code in highlight in posts, Thanks [@enumag]

### Changed

- [#278] **CodingStandard** **EasyCodingStandard** Bumped to **PHP-CS-Fixer 2.4** + applied many related fixes

#### EasyCodingStandard

- [#232] Improved report after all is fixed

- [#255] Fixers are sorted by priority

- [#239] `PHP_EOL` is now default line-ending for PHP-CS-Fixer, Thanks [@dg]

### Fixed

- [#245] **Statie** Fixed Configuration in ParametersProvider

#### EasyCodingStandard

- [#230] Fixed Configuration BC break by PHP-CS-Fixer 2.3

- [#238] Fixed caching invalidation for config including other configs

- [#257] Error is propagated to exit code, Thanks [@dg]

### Deprecated

#### CodingStandard

- [#240] Deprecated `VarPropertyCommentSniff`, use `SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff` instead

- [#264] Deprecated `ClassNamesWithoutPreSlashSniff`, use `\SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff` instead

- [#282] Deprecated `ForbiddenTraitSniff`, was too strict

## [v2.1.0] - 2017-07-04

### Added

- [#165] **CodingStandard** added `ArrayPropertyDefaultValueFixer`; require default values for array property types

    ```php
    class SomeClass
    {
        /**
         * @var int[]
         */
        public $property = []; // here!
    }
    ```

    Thanks [@keradus] and [@SpacePossum] for patient feedback and help with my first Fixer ever

#### EasyCodingStandard

- [#190] Added `show` command to display all loaded checkers

- [#194] Added shorter CLI alternative: `vendor/bin/ecs`

- [#198] Allow local config with `--config` option

- [#217] Added "Did you mean" feature for sniff configuration typos

- [#215] Allow checker with empty configuration; this is possible now:

    ```yml
    checkers:
        PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff:
        # someTemporaryCommentedConfig: value
    ```

    Thanks [@dg]

#### PackageBuilder

- [#197] Added `AbstractCliKernel` for CLI apps bootstrapping

- [#199] Added `ConfigFilePathHelper` for CLI and local config detection

- [#223] `NeonLoader` - validate allowed sections feature added

- [#211] Improve configs - allow including for `*.neon` and `*.yml`, add `NeonLoaderAwareKernelTrait` for `*.neon` support in `Kernel`

#### Statie

- [#197] Add configuration via `statie.neon`

- [#201] AMP support added

### Changed

- [#188] **CodingStandard** add all rules to `README.md`

#### EasyCodingStandard

- [#221] throw nicer exception on Container build fail

- [#214] migrate RunCommand to more flexible Configuration service

#### PackageBuilder

- [#190] `DefinitionCollector::loadCollectorWithType()` now allows multiple `$collectors`

- [#212] Add exception for missing file

#### Statie

- [#224] Use local `statie.neon` config file over global loading + use `underscore_case` (due to Symfony) - **BC BREAK!**

- [#196] Improved message for Latte parser exception

- [#195] Improved NEON parser error exception, closes [#99]

### Fixed

#### EasyCodingStandard

- [b45335] Fix missing `nette\robot-loader` dependency

- [b02535] Fix ChangedFilesDetector for missing config file

## [v2.0.0] - 2017-06-16

### Added

- [#179] **EasyCodingStandard** check for unused skipped errors and report them (inspired by [@phpstan])

- [#150] **Statie** decouple Latte related files to FlatWhite sub-package

#### CodingStandard

- [#144] Added new sniffs
    - `Symplify\CodingStandard\Sniffs\Architecture\ForbiddenTraitSniff`
    - `Symplify\CodingStandard\Sniffs\Commenting\VarConstantCommentSniff`
    - `Symplify\CodingStandard\Sniffs\Controller\ControllerRenderMethodLimitSniff`
    - `Symplify\CodingStandard\Sniffs\Controller\InvokableControllerSniff`

- [#149] Added `Symplify\CodingStandard\Sniffs\Classes\EqualInterfaceImplementationSniff`

- [#149] Added `Symplify\CodingStandard\Sniffs\Debug\CommentedOutCodeSniff`

- [#152] Check for duplicated checker added - https://github.com/Symplify/Symplify/pull/152/files#diff-9c8034d27d44f02880909bfad4a7f853

### Changed

- [#155] bump min version to Symfony 3.3

- [#184] **Statie** use `Symfony\DependencyInjection` instead of `Nette\DI`

#### EasyCodingStandard

- [#179] use `Symfony\DependencyInjection` instead of `Nette\DI`, due to [new Symfony 3.3 features](https://www.tomasvotruba.cz/blog/2017/05/07/how-to-refactor-to-new-dependency-injection-features-in-symfony-3-3/)

- [#151] `Nette\DI` config loading style added, parameters are now in Container and sniffs/fixers are registered as services

### Fixed

- [#157] **CodingStandard** fix property docblock sniff for multiple annotations

- [#164] **SymbioticController** fixed typo in nette application request event name, Thanks [@Lexinek]

- [#142] **ControllerAutowire** prevent duplicated controller registraction

### Removed

- [#184] **Statie** dropped translation support, not very extensive and shown unable in practise, implement own simple filter instead

#### CodingStandard

- [#144] Drop sniffs duplicated in 3rd party packages
    - `Symplify\CodingStandard\Sniffs\Commenting\MethodCommentSniff`, use `SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff` instead
    - `Symplify\CodingStandard\Sniffs\Commenting\MethodReturnTypeSniff`, use `SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff` instead

- [#152] Removed unused sniff `Symplify\CodingStandard\Sniffs\Commenting\ComponentFactoryCommentSniff`

[Based on discussion with friends and maintainers](https://www.tomasvotruba.cz/blog/2017/05/29/symplify-packages-deprecations-brought-by-symfony-33/), I've found there are better managed and actively used packages, that provide similar features as few Simplify packages. So **these packages were deprecated**:

- [#170] **EventDispatcher** package deprecated in favor of [contributte/event-dispatcher]

- [#162] **DefaultAutowire** package deprecated in favor of Symfony 3.3 `_defaults` section

- [#186] **ModularLatteFilter** package deprecated in favor of https://github.com/contributte/latte

- [#182] **ModularRouting** package deprecated based poor usage and discussion in [#181]

- [#155] **AutoServiceRegistration** package deprecated
    - Use [@Symfony] 3.3 PSR-4 service autodiscovery

- [#155] **ControllerAutowire** package deprecated
    - Use [@Symfony] 3.3 `AbstractController`
    - Use [@Symfony] 3.3 service PSR-4 autodiscovery

- [#155] **ServiceDefinitionDecorator** package deprecated
    - Use `_instanceof` [@Symfony] 3.3: https://symfony.com/blog/new-in-symfony-3-3-simpler-service-configuration#interface-based-service-configuration

- [#153] **SymfonySecurityVoters** package deprecated, for no practical use

[comment]: # (links to issues, PRs and release diffs)

[#505]: https://github.com/Symplify/Symplify/pull/505
[#488]: https://github.com/Symplify/Symplify/pull/488
[#484]: https://github.com/Symplify/Symplify/pull/484
[#481]: https://github.com/Symplify/Symplify/pull/481
[#480]: https://github.com/Symplify/Symplify/pull/480
[#476]: https://github.com/Symplify/Symplify/pull/476
[#475]: https://github.com/Symplify/Symplify/pull/475
[#474]: https://github.com/Symplify/Symplify/pull/474
[#473]: https://github.com/Symplify/Symplify/pull/473
[#472]: https://github.com/Symplify/Symplify/pull/472
[#471]: https://github.com/Symplify/Symplify/pull/471
[#466]: https://github.com/Symplify/Symplify/pull/466
[#437]: https://github.com/Symplify/Symplify/pull/437
[#487]: https://github.com/Symplify/Symplify/issues/487
[#483]: https://github.com/Symplify/Symplify/issues/483
[#477]: https://github.com/Symplify/Symplify/issues/477
[v3.0.1]: https://github.com/Symplify/Symplify/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/Symplify/Symplify/compare/v2.5.0...v3.0.0
[#452]: https://github.com/Symplify/Symplify/pull/452
[v2.5.0]: https://github.com/Symplify/Symplify/compare/v2.4.0...v2.5.0
[v2.4.0]: https://github.com/Symplify/Symplify/compare/v2.3.0...v2.4.0
[v2.3.0]: https://github.com/Symplify/Symplify/compare/v2.2.0...v2.3.0
[v2.2.0]: https://github.com/Symplify/Symplify/compare/v2.1.0...v2.2.0
[v2.1.0]: https://github.com/Symplify/Symplify/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/Symplify/Symplify/compare/v1.4.10...v2.0.0
[#447]: https://github.com/Symplify/Symplify/pull/447
[#443]: https://github.com/Symplify/Symplify/pull/443
[#442]: https://github.com/Symplify/Symplify/pull/442
[#432]: https://github.com/Symplify/Symplify/pull/432
[#431]: https://github.com/Symplify/Symplify/pull/431
[#430]: https://github.com/Symplify/Symplify/pull/430
[#427]: https://github.com/Symplify/Symplify/pull/427
[#422]: https://github.com/Symplify/Symplify/issues/422
[#421]: https://github.com/Symplify/Symplify/pull/421
[#419]: https://github.com/Symplify/Symplify/pull/419
[#417]: https://github.com/Symplify/Symplify/pull/417
[#412]: https://github.com/Symplify/Symplify/pull/412
[#406]: https://github.com/Symplify/Symplify/pull/406
[#404]: https://github.com/Symplify/Symplify/pull/404
[#399]: https://github.com/Symplify/Symplify/pull/399
[#397]: https://github.com/Symplify/Symplify/pull/397
[#388]: https://github.com/Symplify/Symplify/pull/388
[#385]: https://github.com/Symplify/Symplify/pull/385
[#382]: https://github.com/Symplify/Symplify/pull/382
[#381]: https://github.com/Symplify/Symplify/pull/381
[#379]: https://github.com/Symplify/Symplify/pull/379
[#376]: https://github.com/Symplify/Symplify/pull/376
[#374]: https://github.com/Symplify/Symplify/pull/374
[#372]: https://github.com/Symplify/Symplify/issues/372
[#370]: https://github.com/Symplify/Symplify/pull/370
[#365]: https://github.com/Symplify/Symplify/pull/365
[#360]: https://github.com/Symplify/Symplify/pull/360
[#359]: https://github.com/Symplify/Symplify/pull/359
[#358]: https://github.com/Symplify/Symplify/pull/358
[#356]: https://github.com/Symplify/Symplify/pull/356
[#355]: https://github.com/Symplify/Symplify/pull/355
[#354]: https://github.com/Symplify/Symplify/pull/354
[#347]: https://github.com/Symplify/Symplify/pull/347
[#346]: https://github.com/Symplify/Symplify/pull/346
[#342]: https://github.com/Symplify/Symplify/pull/342
[#338]: https://github.com/Symplify/Symplify/pull/338
[#337]: https://github.com/Symplify/Symplify/pull/337
[#334]: https://github.com/Symplify/Symplify/pull/334
[#332]: https://github.com/Symplify/Symplify/pull/332
[#331]: https://github.com/Symplify/Symplify/pull/331
[#330]: https://github.com/Symplify/Symplify/pull/330
[#328]: https://github.com/Symplify/Symplify/pull/328
[#327]: https://github.com/Symplify/Symplify/pull/327
[#325]: https://github.com/Symplify/Symplify/pull/325
[#320]: https://github.com/Symplify/Symplify/pull/320
[#314]: https://github.com/Symplify/Symplify/pull/314
[#311]: https://github.com/Symplify/Symplify/pull/311
[#305]: https://github.com/Symplify/Symplify/pull/305
[#301]: https://github.com/Symplify/Symplify/pull/301
[#295]: https://github.com/Symplify/Symplify/pull/295
[#290]: https://github.com/Symplify/Symplify/pull/290
[#289]: https://github.com/Symplify/Symplify/pull/289
[#287]: https://github.com/Symplify/Symplify/pull/287
[#285]: https://github.com/Symplify/Symplify/pull/285
[#283]: https://github.com/Symplify/Symplify/pull/283
[#282]: https://github.com/Symplify/Symplify/pull/282
[#281]: https://github.com/Symplify/Symplify/pull/281
[#279]: https://github.com/Symplify/Symplify/pull/279
[#278]: https://github.com/Symplify/Symplify/pull/278
[#276]: https://github.com/Symplify/Symplify/pull/276
[#275]: https://github.com/Symplify/Symplify/issues/275
[#272]: https://github.com/Symplify/Symplify/pull/272
[#267]: https://github.com/Symplify/Symplify/pull/267
[#264]: https://github.com/Symplify/Symplify/pull/264
[#262]: https://github.com/Symplify/Symplify/pull/262
[#257]: https://github.com/Symplify/Symplify/pull/257
[#256]: https://github.com/Symplify/Symplify/pull/256
[#255]: https://github.com/Symplify/Symplify/pull/255
[#245]: https://github.com/Symplify/Symplify/pull/245
[#240]: https://github.com/Symplify/Symplify/pull/240
[#239]: https://github.com/Symplify/Symplify/pull/239
[#238]: https://github.com/Symplify/Symplify/pull/238
[#234]: https://github.com/Symplify/Symplify/pull/234
[#232]: https://github.com/Symplify/Symplify/pull/232
[#230]: https://github.com/Symplify/Symplify/pull/230
[#225]: https://github.com/Symplify/Symplify/pull/225
[#224]: https://github.com/Symplify/Symplify/pull/224
[#223]: https://github.com/Symplify/Symplify/pull/223
[#222]: https://github.com/Symplify/Symplify/pull/222
[#221]: https://github.com/Symplify/Symplify/pull/221
[#217]: https://github.com/Symplify/Symplify/pull/217
[#215]: https://github.com/Symplify/Symplify/pull/215
[#214]: https://github.com/Symplify/Symplify/pull/214
[#212]: https://github.com/Symplify/Symplify/pull/212
[#211]: https://github.com/Symplify/Symplify/pull/211
[#201]: https://github.com/Symplify/Symplify/pull/201
[#199]: https://github.com/Symplify/Symplify/pull/199
[#198]: https://github.com/Symplify/Symplify/pull/198
[#197]: https://github.com/Symplify/Symplify/pull/197
[#196]: https://github.com/Symplify/Symplify/pull/196
[#195]: https://github.com/Symplify/Symplify/pull/195
[#194]: https://github.com/Symplify/Symplify/pull/194
[#190]: https://github.com/Symplify/Symplify/pull/190
[#188]: https://github.com/Symplify/Symplify/pull/188
[#186]: https://github.com/Symplify/Symplify/pull/186
[#184]: https://github.com/Symplify/Symplify/pull/184
[#183]: https://github.com/Symplify/Symplify/pull/183
[#182]: https://github.com/Symplify/Symplify/pull/182
[#179]: https://github.com/Symplify/Symplify/pull/179
[#173]: https://github.com/Symplify/Symplify/pull/173
[#170]: https://github.com/Symplify/Symplify/pull/170
[#165]: https://github.com/Symplify/Symplify/pull/165
[#164]: https://github.com/Symplify/Symplify/pull/164
[#162]: https://github.com/Symplify/Symplify/pull/162
[#157]: https://github.com/Symplify/Symplify/pull/157
[#155]: https://github.com/Symplify/Symplify/pull/155
[#153]: https://github.com/Symplify/Symplify/pull/153
[#152]: https://github.com/Symplify/Symplify/pull/152
[#151]: https://github.com/Symplify/Symplify/pull/151
[#150]: https://github.com/Symplify/Symplify/pull/150
[#149]: https://github.com/Symplify/Symplify/pull/149
[#144]: https://github.com/Symplify/Symplify/pull/144
[#142]: https://github.com/Symplify/Symplify/pull/142
[d350b1]: https://github.com/Symplify/Symplify/commit/d350b1c5ff8f763a41907068d6a5e9cbb6a13379
[bf8024]: https://github.com/Symplify/Symplify/commit/bf802422b9528946a8bd7e7f0331d858a9bf5740
[bc0cb0]: https://github.com/Symplify/Symplify/commit/bc0cb09d5e5166830ba4ad95fd4d0ba8f4bcacf4
[881577]: https://github.com/Symplify/Symplify/commit/881577af893ed1e73260f713153004be78aaf101
[430fc5]: https://github.com/Symplify/Symplify/commit/430fc59da26c5a43ccdbb2d2f8d75b2edff4aea6
[33f28a]: https://github.com/Symplify/Symplify/commit/33f28a03daafa76f7bbdad380348a736650e357b
[0ab538]: https://github.com/Symplify/Symplify/commit/0ab538bd53c971f6a7163485230a44658f613768
[#99]: https://github.com/Symplify/Symplify/issues/99
[#402]: https://github.com/Symplify/Symplify/issues/402
[#386]: https://github.com/Symplify/Symplify/issues/386
[#333]: https://github.com/Symplify/Symplify/issues/333
[#181]: https://github.com/Symplify/Symplify/issues/181
[#266]: https://github.com/Symplify/Symplify/pull/266
[#258]: https://github.com/Symplify/Symplify/pull/258
[#252]: https://github.com/Symplify/Symplify/pull/252
[#243]: https://github.com/Symplify/Symplify/pull/243
[@webrouse]: https://github.com/webrouse
[@vlastavesely]: https://github.com/vlastavesely
[@phpstan]: https://github.com/phpstan
[@ostrolucky]: https://github.com/ostrolucky
[@mzstic]: https://github.com/mzstic
[@keradus]: https://github.com/keradus
[@enumag]: https://github.com/enumag
[@dg]: https://github.com/dg
[@crazko]: https://github.com/crazko
[@chemix]: https://github.com/chemix
[@Symfony]: https://github.com/Symfony
[@SpacePossum]: https://github.com/SpacePossum
[@RectorPHP]: https://github.com/RectorPHP
[@Lexinek]: https://github.com/Lexinek
[b45335]: https://github.com/Symplify/Symplify/commit/b45335c4e3674f7d0348ab31f1c359695d9d1d51
[b02535]: https://github.com/Symplify/Symplify/commit/b025353e06364cdb06f81d535dcb1d70b76b3a53
[9b154d]: https://github.com/Symplify/Symplify/commit/9b154d9b6e88075e14b6812613bce7c1a2a79daa
[9a9c0e]: https://github.com/Symplify/Symplify/commit/9a9c0e61d0b7af073d3819e4c4798a251eca1f14
[#508]: https://github.com/Symplify/Symplify/pull/508
[contributte/event-dispatcher]: https://github.com/contributte/event-dispatcher
[3fce4e]: https://github.com/Symplify/Symplify/commit/3fce4e4168a67efe3d7e19be5fd8dc231d352c76
[v3.1.0]: https://github.com/Symplify/Symplify/compare/v3.0.1...v3.1.0
[#558]: https://github.com/Symplify/Symplify/pull/558
[@carusogabriel]: https://github.com/carusogabriel
[v3.2.0]: https://github.com/Symplify/Symplify/compare/v3.1.0...v3.2.0
[#577]: https://github.com/Symplify/Symplify/pull/577
[#576]: https://github.com/Symplify/Symplify/pull/576
[#574]: https://github.com/Symplify/Symplify/pull/574
[#573]: https://github.com/Symplify/Symplify/pull/573
[#570]: https://github.com/Symplify/Symplify/pull/570
[#562]: https://github.com/Symplify/Symplify/pull/562
[#560]: https://github.com/Symplify/Symplify/pull/560
[#559]: https://github.com/Symplify/Symplify/pull/559
[@tomasfejfar]: https://github.com/tomasfejfar
[@muglug]: https://github.com/muglug
[#583]: https://github.com/Symplify/Symplify/pull/583
[#578]: https://github.com/Symplify/Symplify/pull/578
[caf08e]: https://github.com/Symplify/Symplify/commit/caf08e93b2627e1e981493349957f4e49d55cd6a
[59bdfc]: https://github.com/Symplify/Symplify/commit/59bdfc3c0d4945f946d17f127e6a329384d5bab8
[257e5b]: https://github.com/Symplify/Symplify/commit/257e5bc68b9341f8fbe1e306d08f736038d6d626
[1fcc92]: https://github.com/Symplify/Symplify/commit/1fcc927258710b0a03a806fa1661ed0179a5aaf7
[#640]: https://github.com/Symplify/Symplify/pull/640
[#638]: https://github.com/Symplify/Symplify/issues/638
[#637]: https://github.com/Symplify/Symplify/issues/637
[#633]: https://github.com/Symplify/Symplify/pull/633
[#612]: https://github.com/Symplify/Symplify/pull/612
[#607]: https://github.com/Symplify/Symplify/issues/607
[#606]: https://github.com/Symplify/Symplify/pull/606
[#603]: https://github.com/Symplify/Symplify/pull/603
[#599]: https://github.com/Symplify/Symplify/pull/599
[#598]: https://github.com/Symplify/Symplify/pull/598
[#597]: https://github.com/Symplify/Symplify/issues/597
[#595]: https://github.com/Symplify/Symplify/pull/595
[#594]: https://github.com/Symplify/Symplify/issues/594
[#591]: https://github.com/Symplify/Symplify/pull/591
[#589]: https://github.com/Symplify/Symplify/pull/589
[#585]: https://github.com/Symplify/Symplify/pull/585
[#584]: https://github.com/Symplify/Symplify/pull/584
[#647]: https://github.com/Symplify/Symplify/pull/647
[#645]: https://github.com/Symplify/Symplify/pull/645
[#654]: https://github.com/Symplify/Symplify/pull/654
[#651]: https://github.com/Symplify/Symplify/pull/651
[#650]: https://github.com/Symplify/Symplify/issues/650
[#656]: https://github.com/Symplify/Symplify/pull/656
[@marmichalski]: https://github.com/marmichalski
[#661]: https://github.com/Symplify/Symplify/pull/661
[#660]: https://github.com/Symplify/Symplify/pull/660
[#690]: https://github.com/Symplify/Symplify/pull/690
[#688]: https://github.com/Symplify/Symplify/pull/688
[#709]: https://github.com/Symplify/Symplify/pull/709
[#708]: https://github.com/Symplify/Symplify/pull/708
[#707]: https://github.com/Symplify/Symplify/pull/707
[#705]: https://github.com/Symplify/Symplify/pull/705
[#704]: https://github.com/Symplify/Symplify/pull/704
[#703]: https://github.com/Symplify/Symplify/pull/703
[#700]: https://github.com/Symplify/Symplify/pull/700
[#698]: https://github.com/Symplify/Symplify/pull/698
[#693]: https://github.com/Symplify/Symplify/pull/693
[#692]: https://github.com/Symplify/Symplify/pull/692
[#691]: https://github.com/Symplify/Symplify/pull/691
[#680]: https://github.com/Symplify/Symplify/pull/680
[@OndraM]: https://github.com/OndraM
[v4.0.0alpha3]: https://github.com/Symplify/Symplify/compare/v3.2.0...v4.0.0alpha3
[#722]: https://github.com/Symplify/Symplify/pull/722
[#721]: https://github.com/Symplify/Symplify/pull/721
[#720]: https://github.com/Symplify/Symplify/pull/720
[#717]: https://github.com/Symplify/Symplify/pull/717
[#715]: https://github.com/Symplify/Symplify/pull/715
[#713]: https://github.com/Symplify/Symplify/pull/713
[#712]: https://github.com/Symplify/Symplify/pull/712
[#702]: https://github.com/Symplify/Symplify/issues/702
[#701]: https://github.com/Symplify/Symplify/issues/701
[#747]: https://github.com/Symplify/Symplify/pull/747
[#744]: https://github.com/Symplify/Symplify/pull/744
[#743]: https://github.com/Symplify/Symplify/pull/743
[#742]: https://github.com/Symplify/Symplify/pull/742
[#741]: https://github.com/Symplify/Symplify/pull/741
[#740]: https://github.com/Symplify/Symplify/pull/740
[#732]: https://github.com/Symplify/Symplify/pull/732
[#730]: https://github.com/Symplify/Symplify/pull/730
[#729]: https://github.com/Symplify/Symplify/pull/729
[#749]: https://github.com/Symplify/Symplify/pull/749
[v4.0.0]: https://github.com/Symplify/Symplify/compare/v3.2.0...v4.0.0
[#755]: https://github.com/Symplify/Symplify/pull/755
[#706]: https://github.com/Symplify/Symplify/pull/706
[v4.0.1]: https://github.com/Symplify/Symplify/compare/v4.0.0...v4.0.1
[#759]: https://github.com/Symplify/Symplify/pull/759
[#758]: https://github.com/Symplify/Symplify/pull/758
[#757]: https://github.com/Symplify/Symplify/pull/757
[#750]: https://github.com/Symplify/Symplify/issues/750
[#764]: https://github.com/Symplify/Symplify/pull/764
[#763]: https://github.com/Symplify/Symplify/pull/763
[v4.0.2]: https://github.com/Symplify/Symplify/compare/v4.0.1...v4.0.2
[#769]: https://github.com/Symplify/Symplify/pull/769
[#768]: https://github.com/Symplify/Symplify/pull/768
[#767]: https://github.com/Symplify/Symplify/pull/767
[#766]: https://github.com/Symplify/Symplify/pull/766
