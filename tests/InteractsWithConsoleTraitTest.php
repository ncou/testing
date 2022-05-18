<?php

declare(strict_types=1);

namespace Chiron\Test\Testing;

use Chiron\Console\Console;
use Chiron\Test\Testing\Fixtures\Command\SampleCommand;
use Chiron\Testing\Traits\InteractsWithConsoleTrait;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

//https://github.com/cakephp/cakephp/blob/5.x/tests/TestCase/TestSuite/ConsoleIntegrationTestTraitTest.php

class InteractsWithConsoleTraitTest extends TestCase
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

        $this->runCommand('sample', ['--exit' => 0]);

        $this->assertExitSuccess();
    }

    public function testCommandError(): void
    {
        $this->runCommand('sample', ['--exit' => 1]);

        $this->assertExitFailure();
    }

    public function testCommandErrorCustomCode(): void
    {
        $this->runCommand('sample', ['--exit' => 255]);

        $this->assertExitCode(255);
    }

    protected function console(): Console
    {
        // TODO : créer un ArrayCommandLoader ??? pour éviter de faire un callable ca permettrait de passer simplement le nom de la classe et un new $class() serait effectué !!!
        $commandLoader = new FactoryCommandLoader([
            'sample' => fn () => new SampleCommand(),
        ]);

        return new Console($commandLoader);
    }
}
