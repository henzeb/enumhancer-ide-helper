<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

enum AnotherUserEnum
{
    use Enhancers, Constructor, Macros;

    case User;
    case Admin;
    case Default;
}
