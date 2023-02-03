<?php

namespace Henzeb\Enumhancer\Tests\Unit\Idehelper\Writers;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Idehelper\Writers\FileWriter;

class FileWriterTest extends TestCase
{
    private const TEST_FILE = '/tmp/.ide.enumhancer.php';

    public function testFileWriterWritesFile()
    {
        $writer = new FileWriter(
            new \SplFileInfo(self::TEST_FILE)
        );

        $this->assertFileDoesNotExist(self::TEST_FILE);

        $writer->write('<?php echo "test";');

        $this->assertFileExists(self::TEST_FILE);

        $this->assertEquals(
            '<?php echo "test";',
            \file_get_contents(self::TEST_FILE)
        );

        $writer->write('<?php echo\n"another test";');

        $this->assertEquals(
            '<?php echo\n"another test";',
            \file_get_contents(self::TEST_FILE)
        );
    }

    protected function tearDown(): void
    {
        if (\file_exists(self::TEST_FILE)) {
            \unlink(self::TEST_FILE);
        }
    }
}
