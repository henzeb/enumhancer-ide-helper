<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators\Contracts;

use UnitEnum;
use BackedEnum;
use ReflectionClass;
use Henzeb\Enumhancer\Idehelper\IdeHelperPrinter;

abstract class EnumObjectDecorator implements EnumObject
{
    public function __construct(
        protected readonly EnumObject $enumObject,
        private readonly IdeHelperPrinter $printer
    ) {
    }

    public function getFQCN(): string
    {
        return $this->enumObject->getFQCN();
    }

    public function getShortClassname(): string
    {
        /**
         * @var class-string $class
         */
        $class = $this->getFQCN();
        return (new ReflectionClass($class))->getShortName();
    }

    /**
     * @return IdeHelperPrinter
     */
    protected function getPrinter(): IdeHelperPrinter
    {
        return $this->printer;
    }
}
