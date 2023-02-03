<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper\Decorators;

use Mockery;
use Nette\PhpGenerator\EnumType;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;
use Henzeb\Enumhancer\Idehelper\Decorators\EnumTypeObject;
use Henzeb\Enumhancer\Idehelper\Decorators\ConstructorDecorator;

class ConstructorDecoratorsTest extends MockeryTestCase
{
    public function testShouldDecorate()
    {
        $type = EnumType::from(UserEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'User',
            'UserEnum',
            true,
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'Admin',
            'UserEnum',
            true,
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'Default',
            'UserEnum',
            true,
        )->passthru();


        $comparison = new ConstructorDecorator(
            new EnumTypeObject(
                $type,
                UserEnum::class
            ),
            $IdeHelperPrinter
        );

        $this->assertTrue($comparison->getEnumType() === $type);
        $this->assertEquals(
            '@method static UserEnum User()@method static UserEnum Admin()@method static UserEnum Default()',
            str_replace(["\r", "\n"], '', $type->getComment())
        );
    }

    public function testShouldNotDecorate()
    {
        $type = EnumType::from(SimpleEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->never();


        $comparison = new ConstructorDecorator(
            new EnumTypeObject(
                $type,
                SimpleEnum::class
            ),
            $IdeHelperPrinter
        );

       $comparison->getEnumType();
    }
}
