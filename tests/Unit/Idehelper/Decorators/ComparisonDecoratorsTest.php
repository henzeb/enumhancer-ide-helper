<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper\Decorators;

use Mockery;
use Nette\PhpGenerator\EnumType;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Tests\Fixtures\UserEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;
use Henzeb\Enumhancer\Idehelper\Decorators\EnumTypeObject;
use Henzeb\Enumhancer\Idehelper\Decorators\ComparisonDecorator;

class ComparisonDecoratorsTest extends MockeryTestCase
{
    public function testShouldDecorate()
    {
        $type = EnumType::from(UserEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'isUser',
            'bool',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'isNotUser',
            'bool',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'isAdmin',
            'bool',
        )->passthru();
        $IdeHelperPrinter->expects('printMethodTag')->with(
            'isNotAdmin',
            'bool',
        )->passthru();

        $comparison = new ComparisonDecorator(
            new EnumTypeObject(
                $type,
                UserEnum::class
            ),
            $IdeHelperPrinter
        );

        $this->assertTrue($comparison->getEnumType() === $type);
        $this->assertEquals(
            '@method bool isUser()@method bool isNotUser()@method bool isAdmin()@method bool isNotAdmin()',
            str_replace(["\r", "\n"], '', $type->getComment())
        );
    }

    public function testShouldNotDecorate()
    {
        $type = EnumType::from(SimpleEnum::class);

        $IdeHelperPrinter = Mockery::mock(IdeHelperPrinter::class);
        $IdeHelperPrinter->expects('printMethodTag')->never();

        $comparison = new ComparisonDecorator(
            new EnumTypeObject(
                $type,
                SimpleEnum::class
            ),
            $IdeHelperPrinter
        );

        $comparison->getEnumType();
    }
}
