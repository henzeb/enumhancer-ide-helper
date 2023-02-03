<?php

namespace Henzeb\Enumhancer\Tests\Fixtures
{
    /**
     * @method static UserEnum User()
     * @method static UserEnum Admin()
     * @method static UserEnum Default()
     * @method bool isUser()
     * @method bool isNotUser()
     * @method bool isAdmin()
     * @method bool isNotAdmin()
     * @method UserEnum toUser()
     * @method UserEnum tryToUser()
     * @method UserEnum toAdmin()
     * @method UserEnum tryToAdmin()
     * @method UserEnum toDefault()
     * @method UserEnum tryToDefault()
     * @method string|bool macroMethod(string|bool $var)
     */
    enum UserEnum
    {
    }

    /**
     * @method static BackedUserEnum User()
     * @method static BackedUserEnum Admin()
     * @method static BackedUserEnum Default()
     * @method bool isUser()
     * @method bool isNotUser()
     * @method bool isAdmin()
     * @method bool isNotAdmin()
     * @method BackedUserEnum toUser()
     * @method BackedUserEnum tryToUser()
     * @method BackedUserEnum toAdmin()
     * @method BackedUserEnum tryToAdmin()
     * @method BackedUserEnum toDefault()
     * @method BackedUserEnum tryToDefault()
     */
    enum BackedUserEnum: string
    {
    }

    /**
     * @method static IntBackedUserEnum User()
     * @method static IntBackedUserEnum Admin()
     * @method static IntBackedUserEnum Default()
     * @method bool isUser()
     * @method bool isNotUser()
     * @method bool isAdmin()
     * @method bool isNotAdmin()
     * @method IntBackedUserEnum toUser()
     * @method IntBackedUserEnum tryToUser()
     * @method IntBackedUserEnum toAdmin()
     * @method IntBackedUserEnum tryToAdmin()
     * @method IntBackedUserEnum toDefault()
     * @method IntBackedUserEnum tryToDefault()
     */
    enum IntBackedUserEnum: int
    {
    }

    /**
     * @method static AnotherUserEnum User()
     * @method static AnotherUserEnum Admin()
     * @method static AnotherUserEnum Default()
     * @method bool isUser()
     * @method bool isNotUser()
     * @method bool isAdmin()
     * @method bool isNotAdmin()
     * @method AnotherUserEnum toUser()
     * @method AnotherUserEnum tryToUser()
     * @method AnotherUserEnum toAdmin()
     * @method AnotherUserEnum tryToAdmin()
     * @method AnotherUserEnum toDefault()
     * @method AnotherUserEnum tryToDefault()
     */
    enum AnotherUserEnum
    {
    }
}


namespace Henzeb\Enumhancer\Tests\Fixtures\vendor\Package
{
    /**
     * @method bool isAnEnum()
     * @method bool isNotAnEnum()
     * @method bool isAnotherEnum()
     * @method bool isNotAnotherEnum()
     * @method PackageEnum toAnEnum()
     * @method PackageEnum tryToAnEnum()
     * @method PackageEnum toAnotherEnum()
     * @method PackageEnum tryToAnotherEnum()
     */
    enum PackageEnum
    {
    }
}
