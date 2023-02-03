<?php

namespace Henzeb\Enumhancer\Idehelper\Writers\Contracts;

interface Writer
{
    public function write(string $content): void;
}
