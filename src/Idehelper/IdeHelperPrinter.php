<?php

namespace Henzeb\Enumhancer\Idehelper;

use Nette\Utils\Type;
use Nette\PhpGenerator\Closure;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer as NettePrinter;

class IdeHelperPrinter extends NettePrinter
{
    public string $indentation = '    ';
    public int $linesBetweenMethods = 1;
    public int $linesBetweenUseTypes = 1;

    private ?string $currentNamespace = null;

    public function setCurrentNameSpace(string $namespace): void
    {
        $this->currentNamespace = $namespace;
    }

    private function getCurrentNamespace(): ?string
    {
        return $this->currentNamespace;
    }

    public function printNamespace(PhpNamespace $namespace): string
    {
        $namespace->setBracketedSyntax();
        return parent::printNamespace($namespace);
    }

    public function printMethodTag(
        string $name,
        Closure|string $returnType,
        bool $static = false
    ): string {
        $method = $returnType;

        if (is_string($method)) {
            $method = new Closure();
            $method->setReturnType($returnType);
        }

        $returnType = $this->printType(
            $method->getReturnType(),
            $method->isReturnNullable()
        );

        return sprintf(
            '@method %s%s %s%s',
            $static ? 'static ' : '',
            $returnType ?: 'void',
            $name,
            $this->printParameters($method)
        );
    }

    protected function printType(?string $type, bool $nullable): string
    {
        if (empty($type)) {
            return '';
        }

        $types = Type::fromString($type)->getTypes();

        $preparedReturnTypes = $this->prepareReturnTypes($types);

        if ($nullable) {
            $preparedReturnTypes[] = 'null';
        }

        $returnTypes = '';

        if (!empty($preparedReturnTypes)) {
            $returnTypes = \implode('|', $preparedReturnTypes);
        }

        return parent::printType($returnTypes, false);
    }

    protected function cleanSameNamespace(string $fqcn): string
    {
        $namespace = $this->getCurrentNamespace();

        if ($namespace && \str_starts_with($fqcn, '\\' . $namespace)) {
            return \str_replace('\\' . $namespace . '\\', '', $fqcn);
        }

        return $fqcn;
    }

    protected function dump(mixed $var, int $column = 0): string
    {
        $var = parent::dump($var, $column);

        if ((defined($var) && \constant($var)) || \class_exists($var)) {
            return $this->cleanSameNamespace('\\' . $var);
        }

        return $var;
    }

    /**
     * @param Type[] $returnTypes
     * @return Type[]
     */
    protected function prepareReturnTypes(array $returnTypes): array
    {
        foreach ($returnTypes as $key => $type) {
            if ($this->typeIsClass($type)) {
                $returnTypes[$key] = Type::fromString(
                    $this->cleanSameNamespace(
                        '\\' . $type->getSingleName()
                    )
                );
            }
        }
        return $returnTypes;
    }

    private function typeIsClass(Type $type): bool
    {
        return $type->isClass()
            && !$type->isClassKeyword()
            && $type->getSingleName()
            && \class_exists($type->getSingleName());
    }
}
