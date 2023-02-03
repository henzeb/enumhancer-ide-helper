<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

enum BackedUserEnum: string
{
    use Enhancers, Constructor, Macros;

    case User = 'user';
    case Admin = 'admin';
    case Default = 'default';
}
