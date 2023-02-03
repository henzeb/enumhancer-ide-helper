<?php

namespace Henzeb\Enumhancer\Idehelper\Writers;

use Henzeb\Enumhancer\Idehelper\Writers\Contracts\Writer;

class FileWriter implements Writer
{
    public function __construct(private readonly \SplFileInfo $file)
    {
    }

    public function write(string $content): void
    {
        \file_put_contents($this->file->getPathname(), $content);
    }
}
