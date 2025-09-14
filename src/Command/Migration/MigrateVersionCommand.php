<?php

declare(strict_types=1);
/**
 * /src/Command/Migration/MigrateVersionCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Migration;

use Redis;
use RedisException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class MigrateVersionCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:migration:version')]
class MigrateVersionCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    private string $fromVersion = '1.14.0';
    private string $toVersion = '1.15.0';

    /**
     * MigrateVersionCommand constructor.
     *
     * @param Redis $redisClient
     */
    public function __construct(private readonly Redis $redisClient)
    {
        parent::__construct();
        $this
            ->setDescription("Console command to migrate from version $this->fromVersion to $this->toVersion")
            ->addOption(
                'somenteModulos',
                'm',
                InputOption::VALUE_NONE,
                'Executa o migration apenas dos módulos. Default: false.'
            )
            ->addArgument(
                'modulos',
                InputArgument::OPTIONAL,
                'Relação de módulos que serão atualizados',
                []
            );
    }

    /**
     * @throws RedisException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $somenteModulos = $input->hasOption('somenteModulos');
        $modulos = $input->getArgument('modulos');

        if ($input->isInteractive()) {
            $io->info('Migration from '.$this->fromVersion.' to '.$this->toVersion.' started');
        }

        if ($this->redisClient->get('maintenance')) {
            throw new RuntimeException('Já temos uma migração em andamento!');
        }

        $this->redisClient->flushall();

        if ($input->isInteractive()) {
            $io->info('Redis flushall');
        }

        $this->redisClient->set('maintenance', $this->toVersion);

        if ($input->isInteractive()) {
            $io->info('Database migration started');
        }

        $migrations = array_filter(
            array_map(
                function (string $m) {
                    $map = [
                        'DoctrineMigrations\\Version20240223223521Consultivo' => ['consultivo'],
                        'DoctrineMigrations\\Version20240223223521Disciplinar' => ['disciplinar'],
                        'DoctrineMigrations\\Version20240223223521ECarta' => ['e-carta', 'ecarta'],
                        'DoctrineMigrations\\Version20240223223521GestaoDevedor' => ['gestao', 'gestao-devedor'],
                        'DoctrineMigrations\\Version20240223223521Judicial' => ['judicial'],
                        'DoctrineMigrations\\Version20240223223521ReceitaFederal' => ['receita', 'receita-federal'],
                    ];
                    foreach ($map as $migration => $nomesPossiveisModulos) {
                        if (in_array(mb_strtolower($m), $nomesPossiveisModulos)) {
                            return $migration;
                        }
                    }

                    return null;
                },
                $modulos
            )
        );

        foreach ($migrations as $migration) {
            $this->executeMigration($input, $output, $migration);
        }

        if (!$somenteModulos) {
            $this->executeMigration($input, $output, 'DoctrineMigrations\\Version20240223223521');
        }

        $this->redisClient->del('maintenance');

        return self::SUCCESS;
    }

    /**
     * @throws RedisException
     */
    protected function executeMigration(InputInterface $input, OutputInterface $output, string $class): void
    {
        $io = $this->getSymfonyStyle($input, $output);

        $process = new Process(
            [
                'bin/console',
                'doctrine:migrations:execute',
                '--env='.$input->getOption('env'),
                '--no-debug',
                '--no-interaction',
                '--up',
                $class,
            ]
        );

        $process->setTimeout(3600);
        $process->setIdleTimeout(3600);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->redisClient->del('maintenance');
            throw new ProcessFailedException($process);
        }

        if ($input->isInteractive()) {
            $io->info('Database migration completed');
        }

        if ($input->isInteractive()) {
            $io->success('Migration completed');
        }
    }
}
