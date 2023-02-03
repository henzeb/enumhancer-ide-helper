<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

enum UserEnum
{
    use Enhancers, Constructor, Macros;

    case User;
    case Admin;
    case Default;
}
