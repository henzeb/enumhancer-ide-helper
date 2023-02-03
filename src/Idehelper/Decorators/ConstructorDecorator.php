<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators;

use Nette\PhpGenerator\EnumType;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Idehelper\Decorators\Contracts\EnumObjectDecorator;

class ConstructorDecorator extends EnumObjectDecorator
{
    public function getEnumType(): EnumType
    {
        $enumType = $this->enumObject->getEnumType();

        if (EnumImplements::constructor($this->getFQCN())) {
            foreach ($this->getFQCN()::cases() as $case) {
                $enumType->addComment(
                    $this->getPrinter()->printMethodTag($case->name, $this->getShortClassname(), true)
                );
            }
        }
        return $enumType;
    }
}
