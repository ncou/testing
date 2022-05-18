<?php

declare(strict_types=1);

namespace Chiron\Test\Testing\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SampleCommand extends Command
{
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
    protected static $defaultName = 'sample';

    protected function configure(): void
    {
        $this->addOption('exit', null, InputOption::VALUE_REQUIRED, 'Exit code value');
        //$this->addOption('exit', null, InputOption::VALUE_REQUIRED, 'Exit code value');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $input->getOption('exit');
    }
}
