<?php

namespace Henzeb\Enumhancer\Tests\Feature\IdeHelper;

use Mockery;
use Composer\Config;
use Composer\Composer;
use Composer\Script\Event;
use Composer\Autoload\ClassLoader;
use Henzeb\Enumhancer\Helpers\Enumhancer;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Idehelper\EnumIdeHelper;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedUserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\AnotherUserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedUserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\vendor\package\PackageEnum;

class EnumIdeHelperTest extends MockeryTestCase
{
    public function testShouldDoNothingWhenNonDevMode(): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('isDevMode')->andReturn(false);

        EnumIdeHelper::postAutoloadDump($event);
    }

    public function testShouldNotGenerateIfNoEnums(): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('isDevMode')->andReturn(true);

        $composer = Mockery::mock(Composer::class);
        $config = Mockery::mock(Config::class);
        $config->expects('get')->with('vendor-dir')->andReturn('vendor');
        $composer->expects('getConfig')->andReturn($config);
        $event->expects('getComposer')->andReturn($composer);

        EnumIdeHelper::postAutoloadDump($event);

        $this->assertFileDoesNotExist('./_enumhancer.php');

    }

    public function testShouldGenerate(): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('isDevMode')->andReturn(true);

        $composer = Mockery::mock(Composer::class);
        $config = Mockery::mock(Config::class);
        $config->expects('get')->with('vendor-dir')->andReturn('vendor');
        $composer->expects('getConfig')->andReturn($config);
        $event->expects('getComposer')->andReturn($composer);

        UserEnum::macro(
            'macroMethod',
            function (string|bool $var): bool|string {
                return $var;
            }
        );

        $classLoader = new ClassLoader('vendor');
        $classLoader->addClassMap([
            UserEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../../UserEnum.php',
            BackedUserEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../../BackedUserEnum.php',
            IntBackedUserEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../../IntBackedUserEnum.php',
            AnotherUserEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../../AnotherUserEnum.php',
            PackageEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../Package/PackageEnum.php'
        ]);
        $classLoader->register(true);

        EnumIdeHelper::postAutoloadDump($event);

        $this->assertFileExists('./_enumhancer.php');

        $this->assertFileEquals(__DIR__ . '/../../Fixtures/_enumhancer.php', './_enumhancer.php');
    }

    protected function tearDown(): void
    {
        UserEnum::flushMacros();

        if (\file_exists('./_enumhancer.php')) {
             \unlink('./_enumhancer.php');
        }
    }
}
