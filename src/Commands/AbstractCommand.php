<?php

namespace Arrilot\BitrixMigrations\Commands;

use DomainException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Configures the current command.
     *
     * @param string $message
     */
    protected function abort($message = '')
    {
        if ($message) {
            $this->error($message);
        }

        $this->error('Abort!');

        throw new DomainException();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code.
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        try {
            return $this->fire() ?? 0;
        } catch (DomainException $e) {
            return 1;
        }
    }

    /**
     * Echo an error message.
     *
     * @param string$message
     */
    protected function error($message)
    {
        $this->output->writeln("<error>{$message}</error>");
    }

    /**
     * Echo an info.
     *
     * @param string $message
     */
    protected function info($message)
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * Echo a message.
     *
     * @param string $message
     */
    protected function message($message)
    {
        $this->output->writeln("{$message}");
    }

    /**
     * Execute the console command.
     */
    abstract protected function fire(): ?int;
}
