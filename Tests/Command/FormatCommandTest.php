<?php

namespace Decline\TransformatBundle\Tests\Command;

use Decline\TransformatBundle\Command\FormatCommand;
use Decline\TransformatBundle\Tests\ContainerTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class FormatCommandTest
 * @package Decline\TransformatBundle\Tests\Command
 */
class FormatCommandTest extends ContainerTestCase
{

    const EXECUTE_OUTPUT_RESULT_OK = '[OK] Done.';

    const EXECUTE_OUTPUT_RESULT_FAIL = '[ERROR] No supported files could be found in the configured directory';

    const EXECUTE_OUTPUT_RESULT_FAIL_TRANS_UNITS = '[ERROR] no-trans-units.de.xlf: No trans-units could be found';

    const EXECUTE_OUTPUT_RESULT_FAIL_DUPLICATE_KEY = '[ERROR] duplicate-keys.de.xlf: Duplicate translation key';

    /**
     * Tests the execute method the FormatCommand
     */
    public function testExecute()
    {
        list($result, $output) = $this->runCommand([FormatCommand::COMMAND_NAME]);

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $this->assertStringEndsWith(self::EXECUTE_OUTPUT_RESULT_OK, trim($output->fetch()));
    }

    /**
     * Tests the execute method the FormatCommand with a non-existing file
     */
    public function testExecuteWithNonExistingFile()
    {
        list($result, $output) = $this->runCommand([FormatCommand::COMMAND_NAME, 'foo/bar/foobar.xlf']);

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $this->assertContains(self::EXECUTE_OUTPUT_RESULT_FAIL, $output->fetch());
    }

    /**
     * Tests the execute method the FormatCommand with a file that should be ignored because of its file-ending
     */
    public function testExecuteWithIgnoredFile()
    {
        list($result, $output) = $this->runCommand([FormatCommand::COMMAND_NAME, 'ignored.txt']);

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $this->assertContains(self::EXECUTE_OUTPUT_RESULT_FAIL, $output->fetch());
    }

    /**
     * Tests the execute method the FormatCommand with a file that should generate an error because of missing trans-units
     */
    public function testExecuteWithoutTransUnits()
    {
        list($result, $output) = $this->runCommand(
            [FormatCommand::COMMAND_NAME, '../translations-faulty/no-trans-units.de.xlf']
        );

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $this->assertContains(self::EXECUTE_OUTPUT_RESULT_FAIL_TRANS_UNITS, $output->fetch());
    }

    /**
     * Tests the execute method the FormatCommand with a file that should generate an error because of duplicate keys
     */
    public function testExecuteWithDuplicateKeys()
    {
        list($result, $output) = $this->runCommand(
            [FormatCommand::COMMAND_NAME, '../translations-faulty/duplicate-keys.de.xlf']
        );

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $this->assertContains(self::EXECUTE_OUTPUT_RESULT_FAIL_DUPLICATE_KEY, $output->fetch());
    }

    /**
     * Runs the command and returns the result of it along with the output object
     * @param array $argv
     * @return array
     */
    private function runCommand(array $argv = [])
    {
        $input = new ArgvInput($argv);
        $output = new BufferedOutput();

        // create Command and set container
        $formatCommand = new FormatCommand();
        $formatCommand->setContainer(self::$container);

        // run command
        $result = $formatCommand->run($input, $output);

        return [$result, $output];
    }
}