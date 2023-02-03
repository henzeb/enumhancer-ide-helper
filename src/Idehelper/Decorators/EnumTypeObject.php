<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators;

use Henzeb\Enumhancer\Idehelper\Decorators\Contracts\EnumObject;
use Nette\PhpGenerator\EnumType;

class EnumTypeObject implements EnumObject
{
    public function __construct(
        private readonly EnumType $enumType,
        private readonly string $enumFCQN
    ) {
    }

    public function getEnumType(): EnumType
    {
        return $this->enumType;
    }

    public function getFQCN(): string
    {
        return $this->enumFCQN;
    }
}
