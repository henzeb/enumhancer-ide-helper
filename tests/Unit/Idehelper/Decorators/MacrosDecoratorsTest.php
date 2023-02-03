<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper\Decorators;

use Mockery;
use Nette\PhpGenerator\EnumType;
use Henzeb\Enumhancer\Helpers\Enumhancer;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;
use Henzeb\Enumhancer\Idehelper\Decorators\EnumTypeObject;
use Henzeb\Enumhancer\Idehelper\Decorators\MacrosDecorator;

class MacrosDecoratorsTest extends MockeryTestCase
{
    public function testShouldDecorate()
    {
        UserEnum::macro(
            'rand',
            static function (): UserEnum {
            }
        );

        UserEnum::macro(
            'toJson',
            function (): string {
            }
        );

        $type = EnumType::from(UserEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->withSomeOfArgs(
            'rand',
            true,
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->withSomeOfArgs(
            'toJson',
            false,
        )->passthru();

        $comparison = new MacrosDecorator(
            new EnumTypeObject(
                $type,
                UserEnum::class
            ),
            $IdeHelperPrinter
        );

        $this->assertTrue($comparison->getEnumType() === $type);
        $this->assertEquals(
            '@method static \Henzeb\Enumhancer\Tests\Fixtures\UserEnum rand()@method string toJson()',
            str_replace(["\r", "\n"], '', $type->getComment())
        );
    }

    public function testShouldNotDecorate()
    {
        $type = EnumType::from(SimpleEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->never();

        $comparison = new MacrosDecorator(
            new EnumTypeObject(
                $type,
                SimpleEnum::class
            ),
            $IdeHelperPrinter
        );

        $comparison->getEnumType();
    }

    protected function tearDown(): void
    {
        UserEnum::flushMacros();
        Enumhancer::flushMacros();
    }
}
