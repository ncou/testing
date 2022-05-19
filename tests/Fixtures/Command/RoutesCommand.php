<?php

declare(strict_types=1);

namespace Chiron\Test\Testing\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RoutesCommand extends Command
{
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
    protected static $defaultName = 'routes';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Method', 'Path', 'Handler'])
            ->setRows([
                ['GET', '/path/to/controller/one', 'controller(one)'],
                ['POST', '/path/to/controller/two', 'controller(two)'],
            ]);
        $table->render();

        return self::SUCCESS;
    }
}
