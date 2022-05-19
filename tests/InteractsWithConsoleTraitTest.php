<?php

declare(strict_types=1);

namespace Chiron\Test\Testing;

use Chiron\Console\Console;
use Chiron\Dev\Tools\TestSuite\AbstractTestCase;
use Chiron\Test\Testing\Fixtures\Command\RoutesCommand;
use Chiron\Test\Testing\Fixtures\Command\SampleCommand;
use Chiron\Testing\Traits\InteractsWithConsoleTrait;
use PHPUnit\Framework\AssertionFailedError;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

//https://github.com/cakephp/cakephp/blob/5.x/tests/TestCase/TestSuite/ConsoleIntegrationTestTraitTest.php

class InteractsWithConsoleTraitTest extends AbstractTestCase
{
    use InteractsWithConsoleTrait;

    public function testCommandSuccess(): void
    {
/*
        assertExitCode
        assertExitSuccess
        assertExitFailure
        assertOutputEmpty
        assertOutputContains
        assertOutputNotContains
        assertOutputRegExp
        assertOutputContainsRow

        assertErrorContains
        assertErrorRegExp
        assertErrorEmpty
*/

        $this->runCommand('sample --exit=0');

        $this->assertExitSuccess();
        $this->assertExitCode(Command::SUCCESS);
    }

    public function testCommandError(): void
    {
        $this->runCommand('sample --exit=1');

        $this->assertExitFailure();
        $this->assertExitCode(Command::FAILURE);
    }

    public function testCommandErrorCustomCode(): void
    {
        $this->runCommand('sample --exit=255');

        $this->assertExitCode(255);
    }

    /**
     * tests failure messages for assertions
     *
     * @param string $assertion Assertion method
     * @param string $message   Expected failure message
     * @param string $command   Command to test
     * @param mixed  ...$rest
     *
     * @dataProvider assertionFailureMessagesProvider
     */
    public function testAssertionFailureMessages(string $assertion, string $message, string $command, mixed ...$rest): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageMatches('#' . preg_quote($message, '#') . '.?#');

        $this->runCommand($command);

        call_user_func_array([$this, $assertion], $rest);
    }

    /**
     * data provider for assertion failure messages
     *
     * @return array<string, string, string, mixed>
     */
    public function assertionFailureMessagesProvider(): array
    {
        return [
            'assertExitCode'          => ['assertExitCode', 'Failed asserting that 1 matches exit code 0', 'routes', Command::FAILURE],
            'assertOutputEmpty'       => ['assertOutputEmpty', 'Failed asserting that output is empty', 'routes'],
            'assertOutputContains'    => ['assertOutputContains', 'Failed asserting that \'missing\' is in output', 'routes', 'missing'],
            'assertOutputNotContains' => ['assertOutputNotContains', 'Failed asserting that \'controller\' is not in output', 'routes', 'controller'],
            'assertOutputRegExp'      => ['assertOutputRegExp', 'Failed asserting that `/missing/` PCRE pattern found in output', 'routes', '/missing/'],
            'assertOutputContainsRow' => ['assertOutputContainsRow', 'Failed asserting that `Array (...)` row was in output', 'routes', ['test', 'missing']],
            'assertErrorContains'     => ['assertErrorContains', 'Failed asserting that \'test\' is in error output', 'routes', 'test'],
            'assertErrorRegExp'       => ['assertErrorRegExp', 'Failed asserting that `/test/` PCRE pattern found in error output', 'routes', '/test/'],
        ];
    }

    protected function console(): Console
    {
        // TODO : créer un ArrayCommandLoader ??? pour éviter de faire un callable ca permettrait de passer simplement le nom de la classe et un new $class() serait effectué !!!
        $commandLoader = new FactoryCommandLoader([
            'sample' => fn () => new SampleCommand(),
            'routes' => fn () => new RoutesCommand(),
        ]);

        return new Console($commandLoader);
    }
}
