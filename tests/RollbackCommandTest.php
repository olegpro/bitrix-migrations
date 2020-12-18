<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Commands\RollbackCommand;
use Arrilot\BitrixMigrations\Migrator;
use Mockery as m;
use Symfony\Component\Console\Tester\CommandTester;

class RollbackCommandTest extends CommandTestCase
{
    public function testItRollbacksNothingIfThereIsNoMigrations(): void
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('getRanMigrations')
            ->willReturn([]);

        $migrator
            ->expects($this->never())
            ->method('rollbackMigration');

        $command = new RollbackCommand($migrator);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Nothing to rollback', $output);
    }

    public function testItRollsBackTheLastMigration(): void
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('getRanMigrations')
            ->willReturn([
                '2014_11_26_162220_foo',
                '2015_11_26_162220_bar',
            ]);

        $migrator
            ->expects($this->once())
            ->method('doesMigrationFileExist')
            ->willReturn(true);

        $migrator
            ->expects($this->once())
            ->method('rollbackMigration');

        $command = new RollbackCommand($migrator);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Rolled back: 2015_11_26_162220_bar', $output);
    }

    public function testItRollbackNonExistingMigration(): void
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('getRanMigrations')
            ->willReturn([
                '2014_11_26_162220_foo',
                '2015_11_26_162220_bar',
            ]);

        $migrator
            ->expects($this->once())
            ->method('doesMigrationFileExist')
            ->willReturn(false);

        $migrator
            ->expects($this->never())
            ->method('rollbackMigration');

        $command = new RollbackCommand($migrator);

        $command->setHelperSet(
            new \Symfony\Component\Console\Helper\HelperSet([
                new \Symfony\Component\Console\Helper\QuestionHelper()
            ])
        );

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Do you want to mark it as rolled back', $output);
    }
}
