<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

enum IntBackedUserEnum: int
{
    use Enhancers, Constructor, Macros;

    case User = 1;
    case Admin = 2;
    case Default = 4;
}
