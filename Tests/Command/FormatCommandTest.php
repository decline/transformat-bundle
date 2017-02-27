<?php

namespace Decline\TransformatBundle\Tests\Command;

use Decline\TransformatBundle\Command\FormatCommand;
use Decline\TransformatBundle\Tests\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class FormatCommandTest
 * @package Decline\TransformatBundle\Tests\Command
 */
class FormatCommandTest extends KernelTestCase
{

    /**
     * The expected result of the console output
     * @var string
     */
    const EXECUTE_EXPECTED_OUTPUT_RESULT = '[OK] Done.';

    /**
     * The class of the Kernel to boot for this test
     * @var string
     */
    protected static $class = AppKernel::class;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    /**
     * Tests the execute method the FormatCommand
     */
    public function testExecute() {
        $input = new ArgvInput([FormatCommand::COMMAND_NAME]);
        $output = new BufferedOutput();

        // create Command and set container
        $formatCommand = new FormatCommand();
        $formatCommand->setContainer(self::$kernel->getContainer());

        // run command
        $result = $formatCommand->run($input, $output);

        // check result code
        $this->assertEquals(0, $result);

        // output must end as expected
        $outputResult = trim($output->fetch());
        $this->assertStringEndsWith(self::EXECUTE_EXPECTED_OUTPUT_RESULT, $outputResult);
    }
}