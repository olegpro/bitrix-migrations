<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Commands\InstallCommand;
use Arrilot\BitrixMigrations\Interfaces\DatabaseStorageInterface;
use Symfony\Component\Console\Tester\CommandTester;

class InstallCommandTest extends CommandTestCase
{
    public function testItCreatesMigrationTable(): void
    {
        $database = $this->createMock(DatabaseStorageInterface::class);
        $database
            ->expects($this->once())
            ->method('checkMigrationTableExistence')
            ->willReturn(false);

        $database
            ->expects($this->once())
            ->method('createMigrationTable');

        $command = new InstallCommand('migrations', $database);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Migration table has been successfully created!', $output);
    }

    public function testItDoesNotCreateATableIfItExists(): void
    {
        $database = $this->createMock(DatabaseStorageInterface::class);
        $database
            ->expects($this->once())
            ->method('checkMigrationTableExistence')
            ->willReturn(true);

        $database
            ->expects($this->never())
            ->method('createMigrationTable');

        $command = new InstallCommand('migrations', $database);

        $commandTester = new CommandTester($command);

        $result = $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('already exists', $output);

        $this->assertEquals(1, $result);
    }
}
