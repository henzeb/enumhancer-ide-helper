<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators;

use UnitEnum;
use Nette\PhpGenerator\EnumType;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Idehelper\Decorators\Contracts\EnumObjectDecorator;

class ComparisonDecorator extends EnumObjectDecorator
{
    public function getEnumType(): EnumType
    {
        $enumType = $this->enumObject->getEnumType();

        if ($this->implementsComparison()) {
            $this->addMethods($enumType);
        }

        return $enumType;
    }

    protected function shouldIgnoreMethod(UnitEnum $case): bool
    {
        return strtolower($case->name) === 'default' && EnumImplements::defaults($this->getFQCN());
    }

    private function addMethod(EnumType $type, UnitEnum $case, string $prefix): void
    {
        $type->addComment(
            $this->getPrinter()->printMethodTag($prefix . $case->name, 'bool')
        );
    }

    protected function implementsComparison(): bool
    {
        return EnumImplements::comparison($this->getFQCN());
    }

    protected function addMethods(EnumType $enumType): void
    {
        foreach ($this->getFQCN()::cases() as $case) {
            if ($this->shouldIgnoreMethod($case)) {
                continue;
            }

            $this->addMethod($enumType, $case, 'is');
            $this->addMethod($enumType, $case, 'isNot');
        }
    }
}
