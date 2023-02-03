<?php

namespace Henzeb\Enumhancer\Idehelper\Decorators;

use Closure;
use ReflectionFunction;
use Nette\PhpGenerator\EnumType;
use Henzeb\Enumhancer\Helpers\EnumMacros;
use Nette\PhpGenerator\Closure as PhpClosure;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Idehelper\Decorators\Contracts\EnumObjectDecorator;

class MacrosDecorator extends EnumObjectDecorator
{
    public function getEnumType(): EnumType
    {
        $enumType = $this->enumObject->getEnumType();

        if (EnumImplements::macros($this->getFQCN())) {
            foreach ($this->getFunctionsFor($this->getFQCN()) as $name => $closure) {
                $isStatic = (new ReflectionFunction($closure(...)))->isStatic();

                $closure = PhpClosure::from($closure(...));

                $enumType->addComment(
                    $this->getPrinter()->printMethodTag($name, $closure, $isStatic)
                );
            }
        }

        return $enumType;
    }

    /**
     * @psalm-aram class-string $enumFCQN
     * @return array<string,callable>
     */
    public function getFunctionsFor(string $enumFCQN): array
    {
        return Closure::bind(
            function (string $enumFCQN) {
                return array_merge(
                    EnumMacros::getMacros($enumFCQN),
                );
            },
            null,
            EnumMacros::class
        )(
            $enumFCQN
        );
    }
}
