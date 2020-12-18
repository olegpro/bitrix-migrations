<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Interfaces\DatabaseStorageInterface;

class InstallCommand extends AbstractCommand
{
    protected DatabaseStorageInterface $database;
    protected string $table;

    protected static $defaultName = 'install';

    /**
     * Constructor.
     *
     * @param string $table
     * @param DatabaseStorageInterface $database
     * @param string|null $name
     */
    public function __construct(string $table, DatabaseStorageInterface $database, $name = null)
    {
        $this->table = $table;
        $this->database = $database;

        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setDescription('Create the migration database table');
    }

    /**
     * Execute the console command.
     */
    protected function fire(): ?int
    {
        if ($this->database->checkMigrationTableExistence()) {
            $this->abort("Table \"{$this->table}\" already exists");
        }

        $this->database->createMigrationTable();

        $this->info('Migration table has been successfully created!');

        return 0;
    }
}
