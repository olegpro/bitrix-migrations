<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Commands\MigrateCommand;
use Arrilot\BitrixMigrations\Migrator;
use Symfony\Component\Console\Tester\CommandTester;

class MigrateCommandTest extends CommandTestCase
{
    public function testItMigratesNothingIfThereIsNoOutstandingMigrations()
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('getMigrationsToRun')
            ->willReturn([]);

        $migrator
            ->expects($this->never())
            ->method('runMigration');

        $command = new MigrateCommand($migrator);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Nothing to migrate', $output);
    }

    public function testItMigratesOutstandingMigrations()
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('getMigrationsToRun')
            ->willReturn(['2015_11_26_162220_bar']);

        $migrator
            ->expects($this->once())
            ->method('runMigration')
            ->with('2015_11_26_162220_bar');

        $command = new MigrateCommand($migrator);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Migrated: 2015_11_26_162220_bar.php', $output);
    }
}
