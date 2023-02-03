<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators\Contracts;

use Nette\PhpGenerator\EnumType;

interface EnumObject
{
    public function getEnumType(): EnumType;

    public function getFQCN(): string;
}
