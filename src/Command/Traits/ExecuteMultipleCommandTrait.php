<?php

declare(strict_types=1);
/**
 * /src/Command/Traits/ExecuteMultipleCommandTrait.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Traits;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function array_flip;
use function array_search;
use function array_values;

/**
 * Trait ExecuteMultipleCommandTrait.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Application getApplication()
 */
trait ExecuteMultipleCommandTrait
{
    /**
     * @var mixed[]
     */
    private array $choices = [];

    private SymfonyStyle $io;

    /**
     * Setter method for choices to use.
     *
     * @param mixed[] $choices
     */
    protected function setChoices(array $choices): void
    {
        $this->choices = $choices;
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->write("\033\143");

        while ($command = $this->ask()) {
            $arguments = [
                'command' => $command,
            ];

            $input = new ArrayInput($arguments);

            $cmd = $this->getApplication()->find((string) $command);
            $cmd->run($input, $output);
        }

        if ($input->isInteractive()) {
            $this->io->success('Have a nice day');
        }

        return Command::SUCCESS;
    }

    /**
     * Method to ask user to make choose one of defined choices.
     *
     * @return string|bool
     */
    private function ask()
    {
        $index = array_search(
            $this->io->choice('O que você quer fazer?', array_values($this->choices)),
            array_values($this->choices),
            true
        );

        return array_values(array_flip($this->choices))[(int) $index];
    }
}
