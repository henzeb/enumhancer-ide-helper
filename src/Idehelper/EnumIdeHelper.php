<?php

namespace Henzeb\Enumhancer\Idehelper;

use SplFileInfo;
use Composer\Script\Event;
use Composer\Autoload\ClassLoader;
use Henzeb\Enumhancer\Idehelper\Writers\FileWriter;
use Henzeb\Enumhancer\Idehelper\Writers\Contracts\Writer;

abstract class EnumIdeHelper
{
    /**
     * This method is supposed to be called by a helper from henzeb\enumhancer
     *
     * use Henzeb\Enumhancer\Helpers\IdeHelper::postAutoloadDump instead
     *
     * @param Event $event
     * @return void
     */
    public static function postAutoloadDump(Event $event): void
    {
        /**
         * We can only run things if we are in dev-mode
         */
        if (!$event->isDevMode()) {
            return;
        }

        /**
         * @var string $vendorDir
         */
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        self::generate(
            null,
            new SplFileInfo(
                $vendorDir
            )
        );
    }

    public static function generate(Writer|SplFileInfo $target = null, SplFileInfo $vendor = null): void
    {
        $target = $target ?: new SplFileInfo('./_enumhancer.php');
        $target = $target instanceof SplFileInfo ? new FileWriter($target) : $target;

        $vendor = $vendor ?: new SplFileInfo('vendor');

        $generator = new Generator(
            new Composer(
                $vendor->getPath(),
                ...ClassLoader::getRegisteredLoaders()
            )
        );

        $generator->generate(
            $target
        );
    }
}
