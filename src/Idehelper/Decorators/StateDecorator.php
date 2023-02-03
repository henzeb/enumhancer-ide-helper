<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators;

use UnitEnum;
use Nette\PhpGenerator\EnumType;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Idehelper\Decorators\Contracts\EnumObjectDecorator;

class StateDecorator extends EnumObjectDecorator
{
    public function getEnumType(): EnumType
    {
        $enumType = $this->enumObject->getEnumType();

        if (EnumImplements::state($this->getFQCN())) {
            foreach ($this->getFQCN()::cases() as $case) {
                $this->addComment($enumType, $case, 'to');
                $this->addComment($enumType, $case, 'tryTo');
            }
        }
        return $enumType;
    }

    protected function addComment(EnumType $enumType, UnitEnum $case, string $prefix): void
    {
        $enumType->addComment(
            $this->getPrinter()->printMethodTag($prefix . $case->name, $this->getShortClassname())
        );
    }
}
