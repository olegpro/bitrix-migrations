<?php

namespace Arrilot\Tests\BitrixMigrations;

use Arrilot\BitrixMigrations\Interfaces\DatabaseStorageInterface;
use Arrilot\BitrixMigrations\Interfaces\FileStorageInterface;
use Arrilot\BitrixMigrations\Migrator;
use Arrilot\BitrixMigrations\TemplatesCollection;

class MigratorTest extends CommandTestCase
{
    public function testItCreatesMigration(): void
    {
        $database = $this->createMock(DatabaseStorageInterface::class);

        $files = $this->createMock(FileStorageInterface::class);
        $files
            ->expects($this->once())
            ->method('createDirIfItDoesNotExist');

        $files
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('some content');

        $files
            ->expects($this->once())
            ->method('putContent')
            ->willReturn(1000);

        $migrator = $this->createMigrator($database, $files);

        $this->assertMatchesRegularExpression(
            '/[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}_[0-9]{6}_test_migration/',
            $migrator->createMigration('test_migration', null)
        );
    }

    /**
     * Create migrator.
     */
    protected function createMigrator($database, $files)
    {
        $config = [
            'table' => 'migrations',
            'dir' => 'migrations',
        ];

        $templatesCollection = new TemplatesCollection();
        $templatesCollection->registerBasicTemplates();

        return new Migrator($config, $templatesCollection, $database, $files);
    }
}
