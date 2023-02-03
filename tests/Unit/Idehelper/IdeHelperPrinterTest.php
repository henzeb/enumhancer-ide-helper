<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Nette\PhpGenerator\Closure;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;

class IdeHelperPrinterTest extends TestCase
{
    public function testShouldReturnBracketedInterface()
    {
        $printer = new IdeHelperPrinter();
        $namespace = new PhpNamespace('IdeHelper\Test\Name\Space');
        $namespace->setBracketedSyntax(false);
        $this->assertEquals(
            "namespace IdeHelper\Test\Name\Space\n{\n}\n",
            $printer->printNamespace(
                $namespace
            )
        );
    }

    public function testShouldReturnBracketedInterfaceWhenPrintingMultipleInFile()
    {
        $printer = new IdeHelperPrinter();
        $file = new PhpFile();
        $file->addNamespace('IdeHelper\Test\Name\Space');
        $file->addNamespace('IdeHelper\Test\Name\Space2');

        $this->assertEquals(
            "<?php\n\nnamespace IdeHelper\Test\Name\Space\n{\n}\n\n\nnamespace IdeHelper\Test\Name\Space2\n{\n}\n",
            $printer->printFile(
                $file
            )
        );
    }

    public function providesMethodTagCases(): array
    {
        return [
            ['expected' => 'void myFunction()', 'name' => 'myFunction', 'returnType' => 'void', 'static' => false],
            ['expected' => 'void myFunction()', 'name' => 'myFunction', 'returnType' => '', 'static' => false],
            ['expected' => 'self myFunction()', 'name' => 'myFunction', 'returnType' => 'self', 'static' => false],
            [
                'expected' => 'self|null myFunction()',
                'name' => 'myFunction',
                'returnType' => '?self',
                'static' => false
            ],
            [
                'expected' => 'self|null myFunction()',
                'name' => 'myFunction',
                'returnType' => 'self|null',
                'static' => false
            ],
            ['expected' => 'bool myFunction()', 'name' => 'myFunction', 'returnType' => 'bool', 'static' => false],
            [
                'expected' => 'bool|int myFunction()',
                'name' => 'myFunction',
                'returnType' => 'bool|int',
                'static' => false
            ],
            [
                'expected' => 'static bool|int myFunction()',
                'name' => 'myFunction',
                'returnType' => 'bool|int',
                'static' => true
            ],
            [
                'expected' => 'static \\' . self::class . ' myFunction()',
                'name' => 'myFunction',
                'returnType' => self::class,
                'static' => true
            ],
            [
                'expected' => '\\' . self::class . ' myFunction()',
                'name' => 'myFunction',
                'returnType' => self::class,
                'static' => false
            ],
            [
                'expected' => '\\' . self::class . '|null myFunction()',
                'name' => 'myFunction',
                'returnType' => '?' . self::class,
                'static' => false
            ],
            [
                'expected' => '\\' . self::class . '|null myFunction()',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (): ?IdeHelperPrinterTest {
                }),
                'static' => false
            ],
            [
                'expected' => 'self|null myFunction()',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (): ?self {
                }),
                'static' => false
            ],
            [
                'expected' => 'static self|null myFunction()',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static self|null myFunction(string|null $default)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (?string $default): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static self|null myFunction(self $default)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (self $default): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static self|null myFunction(\\' . IdeHelperPrinterTest::class . ' $default)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (IdeHelperPrinterTest $default): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static self|null myFunction(string $unitTest = \'default\')',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (string $unitTest = 'default'): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static self|null myFunction(\\' . SimpleEnum::class . ' $unitTest = \\' . SimpleEnum::class . '::Diamonds)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (SimpleEnum $unitTest = SimpleEnum::Diamonds): ?self {
                }),
                'static' => true
            ],
            [
                'expected' => 'static SimpleEnum|null myFunction(SimpleEnum $unitTest = SimpleEnum::Diamonds)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (SimpleEnum $unitTest = SimpleEnum::Diamonds): ?SimpleEnum {
                }),
                'static' => true,
                'namespace' => SimpleEnum::class
            ],
            [
                'expected' => 'static SimpleEnum|null myFunction(SimpleEnum $unitTest = null)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (SimpleEnum $unitTest = null): ?SimpleEnum {
                }),
                'static' => true,
                'namespace' => SimpleEnum::class
            ],
            [
                'expected' => 'static SimpleEnum|null myFunction(bool $unitTest = false)',
                'name' => 'myFunction',
                'returnType' => Closure::from(function (bool $unitTest = false): ?SimpleEnum {
                }),
                'static' => true,
                'namespace' => SimpleEnum::class
            ],
            [
                'expected' => 'static void myFunction()',
                'name' => 'myFunction',
                'returnType' => Closure::from(function () {
                }),
                'static' => true,
                'namespace' => SimpleEnum::class
            ]

        ];
    }

    /**
     * @param string $expected
     * @param string $name
     * @param Closure|string $returnType
     * @param bool $static
     * @return void
     * @dataProvider providesMethodTagCases
     * @throws \ReflectionException
     */
    public function testShouldPrintMethodTag(
        string $expected,
        string $name,
        Closure|string $returnType,
        bool $static,
        string $namespace = null
    ) {
        $printer = new IdeHelperPrinter();

        if ($namespace) {
            $printer->setCurrentNameSpace((new ReflectionClass($namespace))->getNamespaceName());
        }

        $this->assertEquals(
            '@method ' . $expected,
            $printer->printMethodTag('myFunction', $returnType, $static)
        );
    }
}
