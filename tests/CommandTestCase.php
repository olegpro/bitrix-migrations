<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Commands\AbstractCommand;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

abstract class CommandTestCase extends TestCase
{
    /**
     * @param $command
     * @param array $input
     *
     * @return mixed
     */
    protected function runCommand(AbstractCommand $command, $input = [])
    {
        return $command->execute(new ArrayInput($input), new NullOutput());
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'table' => 'migrations',
            'dir'   => 'migrations',
        ];
    }
}
