<?php

namespace Henzeb\Enumhancer\Idehelper;

use Composer\Autoload\ClassLoader;

class Composer
{
    /**
     * @var ClassLoader[]
     */
    private array $classLoaders;

    public function __construct(
        private readonly string $vendorDir,
        ClassLoader ...$classLoaders
    ) {
        $this->classLoaders = $classLoaders;
    }

    /**
     * @return array<int,string>
     */
    public function getEnums(): array
    {
        $classes = [];

        foreach ($this->classLoaders as $loader) {
            $classes[] = $this->getUserClassMap($loader);
            $classes[] = $this->getVendorClassMap($loader);
        }
        return array_keys(
            array_merge(...$classes)
        );
    }

    /**
     * @param ClassLoader $loader
     * @return array<string,string>
     */
    private function getUserClassMap(ClassLoader $loader): array
    {
        return array_filter(
            $loader->getClassMap(),
            fn($file, $class) => $this->isUserFile($file) && \enum_exists($class),
            \ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param ClassLoader $loader
     * @return array<string,string>
     */
    private function getVendorClassMap(ClassLoader $loader): array
    {
        return array_filter(
            $loader->getClassMap(),
            fn(string $file) => $this->isVendorFile($file) && $this->isEnumFile($file),
            \ARRAY_FILTER_USE_BOTH
        );
    }

    private function isUserFile(string $file): bool
    {
        return str_contains(
            \str_replace('\\', '/', $file),
            '/' . $this->vendorDir . '/composer/../../'
        );
    }

    private function isVendorFile(string $file): bool
    {
        return !$this->isUserFile($file);
    }

    private function isEnumFile(string $file): bool
    {
        preg_match_all(
            '/^enum\b[\w\s]*:?\s?(string|int)?\s?\n?{/Um',
            \file_get_contents(\str_replace('\\', '/', $file)) ?: '',
            $matches
        );

        return count($matches[0] ?? []) > 0;
    }
}
