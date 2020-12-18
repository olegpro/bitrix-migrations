<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Migrator;

class MigrateCommand extends AbstractCommand
{
    protected Migrator $migrator;

    protected static $defaultName = 'migrate';
    /**
     * Constructor.
     *
     * @param Migrator    $migrator
     * @param string|null $name
     */
    public function __construct(Migrator $migrator, $name = null)
    {
        $this->migrator = $migrator;

        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setDescription('Run all outstanding migrations');
    }

    /**
     * Execute the console command.
     */
    protected function fire(): ?int
    {
        $toRun = $this->migrator->getMigrationsToRun();

        if (!empty($toRun)) {
            foreach ($toRun as $migration) {
                $this->migrator->runMigration($migration);
                $this->message("<info>Migrated:</info> {$migration}.php");
            }
        } else {
            $this->info('Nothing to migrate');
        }

        return 0;
    }
}
