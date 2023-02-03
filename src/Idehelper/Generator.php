<?php

namespace Henzeb\Enumhancer\Idehelper;

use ReflectionEnum;
use ReflectionException;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Idehelper\Writers\Contracts\Writer;
use Henzeb\Enumhancer\Idehelper\Decorators\EnumTypeObject;
use Henzeb\Enumhancer\Idehelper\Decorators\StateDecorator;
use Henzeb\Enumhancer\Idehelper\Decorators\MacrosDecorator;
use Henzeb\Enumhancer\Idehelper\Decorators\ComparisonDecorator;
use Henzeb\Enumhancer\Idehelper\Decorators\ConstructorDecorator;

class Generator
{

    private const DECORATORS = [
        ConstructorDecorator::class,
        ComparisonDecorator::class,
        StateDecorator::class,
        MacrosDecorator::class,
    ];

    public function __construct(
        private readonly Composer $composer
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function generate(Writer $writer): void
    {
        $enums = $this->getEnhancedEnums();

        if (count($enums) === 0) {
            return;
        }

        $file = new PhpFile();
        $printer = new IdeHelperPrinter();

        foreach ($enums as $enumFCQN) {
            $enum = $this->getEnumTypeObject($enumFCQN);
            $namespaceName = (new ReflectionEnum($enumFCQN))->getNamespaceName();

            $printer->setCurrentNameSpace($namespaceName);

            foreach (self::DECORATORS as $decorator) {
                $enum = new $decorator($enum, $printer);
            }

            $this->getNameSpace($file, $namespaceName)->add($enum->getEnumType());
        }

        $writer->write(
            $printer->printFile($file)
        );
    }

    /**
     * @return array<int,string>
     */
    private function getEnhancedEnums(): array
    {
        return array_filter(
            $this->composer->getEnums(),
            EnumImplements::enumhancer(...)
        );
    }

    private function getNameSpace(PhpFile $file, string $namespaceName): PhpNamespace
    {
        return $file->getNamespaces()[$namespaceName]
            ?? $file->addNamespace($namespaceName);
    }

    protected function getEnumTypeObject(string $enumFCQN): EnumTypeObject
    {
        return new EnumTypeObject($this->createEnumType($enumFCQN), $enumFCQN);
    }

    private function createEnumType(string $enumFCQN): EnumType
    {
        $enumName = EnumType::from($enumFCQN)->getName() ?? '';
        $enumType = new EnumType($enumName);
        $reflect = (new ReflectionEnum($enumFCQN));

        if ($reflect->isBacked()) {
            $enumType->setType((string)$reflect->getBackingType());
        }

        return $enumType;
    }
}
