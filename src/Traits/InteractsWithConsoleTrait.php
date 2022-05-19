<?php

declare(strict_types=1);

namespace Chiron\Testing\Traits;

use Chiron\Console\Console;
use Chiron\Testing\Constraint\Console\ContentsContain;
use Chiron\Testing\Constraint\Console\ContentsContainRow;
use Chiron\Testing\Constraint\Console\ContentsEmpty;
use Chiron\Testing\Constraint\Console\ContentsNotContain;
use Chiron\Testing\Constraint\Console\ContentsRegExp;
use Chiron\Testing\Constraint\Console\ExitCode;
use Chiron\Testing\Stub\ConsoleOutput;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
//use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;

//https://github.com/cakephp/cakephp/blob/5.x/src/TestSuite/ConsoleIntegrationTestTrait.php

trait InteractsWithConsoleTrait
{
    private int $exitCode;
    /** @var string[] */
    private array $stdout = [];
    /** @var string[] */
    private array $stderr = [];

    /**
     * Asserts shell exited with the expected code
     *
     * @param int    $expected Expected exit code
     * @param string $message  Failure message
     *
     * @return void
     */
    public function assertExitCode(int $expected, string $message = ''): void
    {
        $this->assertThat($expected, new ExitCode($this->exitCode), $message);
    }

    /**
     * Asserts shell exited with the Command::SUCCESS
     *
     * @param string $message Failure message
     *
     * @return void
     */
    public function assertExitSuccess(string $message = ''): void
    {
        $this->assertThat(Command::SUCCESS, new ExitCode($this->exitCode), $message);
    }

    /**
     * Asserts shell exited with Command::FAILURE
     *
     * @param string $message Failure message
     *
     * @return void
     */
    public function assertExitFailure(string $message = ''): void
    {
        $this->assertThat(Command::FAILURE, new ExitCode($this->exitCode), $message);
    }

    /**
     * Asserts that `stdout` is empty
     *
     * @param string $message The message to output when the assertion fails.
     *
     * @return void
     */
    public function assertOutputEmpty(string $message = ''): void
    {
        $this->assertThat(null, new ContentsEmpty($this->stdout, 'output'), $message);
    }

    /**
     * Asserts `stdout` contains expected output
     *
     * @param string $expected Expected output
     * @param string $message  Failure message
     *
     * @return void
     */
    public function assertOutputContains(string $expected, string $message = ''): void
    {
        $this->assertThat($expected, new ContentsContain($this->stdout, 'output'), $message);
    }

    /**
     * Asserts `stdout` does not contain expected output
     *
     * @param string $expected Expected output
     * @param string $message  Failure message
     *
     * @return void
     */
    public function assertOutputNotContains(string $expected, string $message = ''): void
    {
        $this->assertThat($expected, new ContentsNotContain($this->stdout, 'output'), $message);
    }

    /**
     * Asserts `stdout` contains expected regexp
     *
     * @param string $pattern Expected pattern
     * @param string $message Failure message
     *
     * @return void
     */
    public function assertOutputRegExp(string $pattern, string $message = ''): void
    {
        $this->assertThat($pattern, new ContentsRegExp($this->stdout, 'output'), $message);
    }

    /**
     * Check that a row of cells exists in the output.
     *
     * @param array<string> $row     Row of cells to ensure exist in the output.
     * @param string        $message Failure message.
     *
     * @return void
     */
    public function assertOutputContainsRow(array $row, string $message = ''): void
    {
        $this->assertThat($row, new ContentsContainRow($this->stdout, 'output'), $message);
    }

    /**
     * Asserts `stderr` contains expected output
     *
     * @param string $expected Expected output
     * @param string $message  Failure message
     *
     * @return void
     */
    public function assertErrorContains(string $expected, string $message = ''): void
    {
        $this->assertThat($expected, new ContentsContain($this->stderr, 'error output'), $message);
    }

    /**
     * Asserts `stderr` contains expected regexp
     *
     * @param string $pattern Expected pattern
     * @param string $message Failure message
     *
     * @return void
     */
    public function assertErrorRegExp(string $pattern, string $message = ''): void
    {
        $this->assertThat($pattern, new ContentsRegExp($this->stderr, 'error output'), $message);
    }

    /**
     * Asserts that `stderr` is empty
     *
     * @param string $message The message to output when the assertion fails.
     *
     * @return void
     */
    public function assertErrorEmpty(string $message = ''): void
    {
        $this->assertThat(null, new ContentsEmpty($this->stderr, 'error output'), $message);
    }

/*
    protected function runCommandDebug(string $command, array $args = [], ?OutputInterface $output = null): void
    {
        $output = $output ?? new BufferedOutput();
        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

        $this->runCommand($command, $args, $output);
    }

    protected function runCommandVeryVerbose(string $command, array $args = [], ?OutputInterface $output = null): void
    {
        $output = $output ?? new BufferedOutput();
        $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);

        $this->runCommand($command, $args, $output);
    }
*/
    // TODO : ajouter deux paramétres $debug et $verbose ou $v et $vv à boolean initialisés à false et on pourrait faire un appel du genre ->runCommand('about', verbose: true)
    // TODO : renommer $args en $input ??? + utilité de garder un paramétre $output ????
    /**
     * There are five levels of verbosity:
     *
     *  normal      : no option passed (normal output)
     *  verbose     : -v (more output)
     *  very verbose: -vv (highly extended output)
     *  debug       : -vvv (all debug output)
     *  quiet       : -q (no output)
    */
    //https://github.com/symfony/console/blob/ec3661faca1d110d6c307e124b44f99ac54179e3/Output/OutputInterface.php

    /**
    public const VERBOSITY_QUIET = 16;
    public const VERBOSITY_NORMAL = 32;
    public const VERBOSITY_VERBOSE = 64;
    public const VERBOSITY_VERY_VERBOSE = 128;
    public const VERBOSITY_DEBUG = 256;
    */
    /*
    protected function runCommand_SAVE(string $command, array $args = []): void
    {
        array_unshift($args, $command);

        $input = new ArrayInput($args);
        $output = new BufferedOutput();

        $this->exitCode = $this->console()->run($input, $output); // TODO : vérifier comment ca se passe en cas d'exception et faire un try/catch ????

        $this->stdout = explode(PHP_EOL, $output->fetch());
    }*/

    /**
     * @param string $command
     */
    protected function runCommand(string $command): void
    {
        // TODO : utiliser plutot un StringInput pour la commande !!!! et pas un tableau !!!
        // TODO : utiliser une méthode commandStringToArgs pour convertir la string command en un tableau d'arguments ca sera plus simple !!!
        // https://github.com/cakephp/cakephp/blob/4fdcab51f02573621a337f1d314a0407b35acc8f/src/TestSuite/ConsoleIntegrationTestTrait.php#L272
        // https://github.com/cakephp/cakephp/blob/bf993b65492aa34cd14fa3755642020b82a26f5a/tests/TestCase/TestSuite/ConsoleIntegrationTestTraitTest.php#L185
        //array_unshift($args, $command);

        //$input = new ArrayInput($args);

        $input = new StringInput($command);
        $output = new ConsoleOutput(); // TODO : faire un mock ??? https://github.com/illuminate/testing/blob/731fdff06b909fa751363a07419a884cf5bd47e3/PendingCommand.php#L341

        $this->exitCode = $this->console()->run($input, $output); // TODO : vérifier comment ca se passe en cas d'exception et faire un try/catch ????

        $this->stdout = $output->messages();
        $this->stderr = $output->getErrorOutput()->messages();
    }

    protected function console(): Console
    {
        throw new RuntimeException('You need to implement the method console() when using InteractsWithConsoleTrait');
    }

    /**
     * Creates an $argv array from a command string
     *
     * @param string $command Command string
     *
     * @return array<string>
     */
    // https://github.com/cakephp/cakephp/blob/4fdcab51f02573621a337f1d314a0407b35acc8f/src/TestSuite/ConsoleIntegrationTestTrait.php#L272
    // https://github.com/cakephp/cakephp/blob/bf993b65492aa34cd14fa3755642020b82a26f5a/tests/TestCase/TestSuite/ConsoleIntegrationTestTraitTest.php#L185
    /*
    protected function commandStringToArgs(string $command): array
    {
        $charCount = strlen($command);
        $argv = [];
        $arg = '';
        $inDQuote = false;
        $inSQuote = false;
        for ($i = 0; $i < $charCount; $i++) {
            $char = substr($command, $i, 1);

            // end of argument
            if ($char === ' ' && !$inDQuote && !$inSQuote) {
                if ($arg !== '') {
                    $argv[] = $arg;
                }
                $arg = '';
                continue;
            }

            // exiting single quote
            if ($inSQuote && $char === "'") {
                $inSQuote = false;
                continue;
            }

            // exiting double quote
            if ($inDQuote && $char === '"') {
                $inDQuote = false;
                continue;
            }

            // entering double quote
            if ($char === '"' && !$inSQuote) {
                $inDQuote = true;
                continue;
            }

            // entering single quote
            if ($char === "'" && !$inDQuote) {
                $inSQuote = true;
                continue;
            }

            $arg .= $char;
        }
        $argv[] = $arg;

        return $argv;
    }*/
}
