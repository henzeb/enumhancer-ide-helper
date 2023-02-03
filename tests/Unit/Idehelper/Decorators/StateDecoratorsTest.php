<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper\Decorators;

use Mockery;
use Nette\PhpGenerator\EnumType;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;
use Henzeb\Enumhancer\Idehelper\Decorators\EnumTypeObject;
use Henzeb\Enumhancer\Idehelper\Decorators\StateDecorator;
use Henzeb\Enumhancer\Idehelper\Decorators\ComparisonDecorator;

class StateDecoratorsTest extends MockeryTestCase
{
    public function testShouldDecorate()
    {
        $type = EnumType::from(UserEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'toUser',
            'UserEnum',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'tryToUser',
            'UserEnum',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'toAdmin',
            'UserEnum',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'tryToAdmin',
            'UserEnum',
        )->passthru();

        $IdeHelperPrinter->expects('printMethodTag')->with(
            'toDefault',
            'UserEnum',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'tryToDefault',
            'UserEnum',
        )->passthru();

        $comparison = new StateDecorator(
            new EnumTypeObject(
                $type,
                UserEnum::class
            ),
            $IdeHelperPrinter
        );

        $this->assertTrue($comparison->getEnumType() === $type);
        $this->assertEquals(
            '@method UserEnum toUser()@method UserEnum tryToUser()@method UserEnum toAdmin()@method UserEnum tryToAdmin()@method UserEnum toDefault()@method UserEnum tryToDefault()',
            str_replace(["\r", "\n"], '', $type->getComment())
        );
    }

    public function testShouldNotDecorate()
    {
        $type = EnumType::from(SimpleEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->never();

        $comparison = new StateDecorator(
            new EnumTypeObject(
                $type,
                SimpleEnum::class
            ),
            $IdeHelperPrinter
        );

        $comparison->getEnumType();
    }
}
