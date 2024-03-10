<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper;

use Composer\Autoload\ClassLoader;
use Henzeb\Enumhancer\Idehelper\Composer;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\vendor\Package\PackageEnum;
use Henzeb\Enumhancer\Tests\Fixtures\vendor\Package\PackageIntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\vendor\Package\PackageStringBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\vendor\Package\SomeClass;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use function str_replace;

class ComposerTest extends MockeryTestCase
{
    public function testShouldReturnEmptyArray(): void
    {
        $composer = new Composer('vendor');

        $this->assertEquals([], $composer->getEnums());
    }

    public function testShouldReturnEmptyArrayWithClassmap(): void
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $classLoader->expects('getClassMap')->twice()->andReturn([]);
        $composer = new Composer('vendor', $classLoader);

        $this->assertEquals([], $composer->getEnums());
    }

    public function testShouldReturnEmptyArrayWithNonEnums()
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $classLoader->expects('getClassMap')->twice()->andReturn([
            SomeClass::class => __DIR__ . '/../../Fixtures/vendor/composer/../Package/SomeClass.php'
        ]);
        $composer = new Composer('vendor', $classLoader);

        $this->assertEquals([], $composer->getEnums());
    }

    public function testShouldReturnEmptyArrayWithMultipleClassMaps()
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $classLoader->expects('getClassMap')->twice()->andReturn([]);

        $classLoader2 = Mockery::mock(ClassLoader::class);
        $classLoader2->expects('getClassMap')->twice()->andReturn([]);

        $composer = new Composer('vendor', $classLoader, $classLoader2);

        $this->assertEquals([], $composer->getEnums());
    }

    public function testShouldReturnArrayWithVendorEnums()
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $classLoader->expects('getClassMap')->twice()->andReturn([
            PackageEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../Package/PackageEnum.php',
            PackageIntBackedEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../Package/PackageIntBackedEnum.php',
            PackageStringBackedEnum::class => __DIR__ . '/../../Fixtures/vendor/composer/../Package/PackageStringBackedEnum.php'
        ]);
        $composer = new Composer('vendor', $classLoader);

        $this->assertEquals(
            [
                PackageEnum::class,
                PackageIntBackedEnum::class,
                PackageStringBackedEnum::class
            ],
            $composer->getEnums()
        );
    }

    public static function providesVendorDirs(): array
    {
        return [
            ['vendor'],
            ['alternative/dir']
        ];
    }

    /**
     * @dataProvider providesVendorDirs
     */
    public function testShouldReturnArrayWithUserEnums(string $vendor)
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $classLoader->expects('getClassMap')->twice()->andReturn([
            UserEnum::class => __DIR__ . '/../../Fixtures/' . $vendor . '/composer/../../UserEnum.php'
        ]);
        $composer = new Composer($vendor, $classLoader);

        $this->assertEquals(
            [
                UserEnum::class
            ],
            $composer->getEnums()
        );
    }

    /**
     * @dataProvider providesVendorDirs
     */
    public function testShouldReturnArrayWithUserEnumsBackSlashed(string $vendor)
    {
        $classLoader = Mockery::mock(ClassLoader::class);
        $vendor = str_replace('/', '\\', $vendor);
        $classLoader->expects('getClassMap')->twice()->andReturn([
            UserEnum::class => __DIR__ . '\..\..\Fixtures\\' . $vendor . '\composer\..\..\..\UserEnum.php',
            PackageEnum::class => __DIR__ . '\..\..\Fixtures\\' . $vendor . '\composer\..\Package\PackageEnum.php'
        ]);
        $composer = new Composer($vendor, $classLoader);

        $this->assertEquals(
            [
                UserEnum::class,
                PackageEnum::class
            ],
            $composer->getEnums()
        );
    }
}
