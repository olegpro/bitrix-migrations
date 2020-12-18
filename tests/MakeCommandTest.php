<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Commands\MakeCommand;
use Arrilot\BitrixMigrations\Migrator;
use Symfony\Component\Console\Tester\CommandTester;

class MakeCommandTest extends CommandTestCase
{
    public function testItCreatesAMigrationFile(): void
    {
        $migrator = $this->createMock(Migrator::class);
        $migrator
            ->expects($this->once())
            ->method('createMigration')
            ->willReturn('2015_11_26_162220_bar');

        $command = new MakeCommand($migrator);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'name' => 'test_migration',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Migration created: 2015_11_26_162220_bar.php', $output);
    }
}
