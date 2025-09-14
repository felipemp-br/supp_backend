<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\SolicitacaoAutomatizada;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * AbstractSolicitacaoAutomatizadaCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractSolicitacaoAutomatizadaCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * Constructor.
     *
     * @param LoggerInterface                 $logger
     * @param TransactionManager              $transactionManager
     * @param SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TransactionManager $transactionManager,
        private readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource,
    ) {
        parent::__construct($this->getName());
    }

    /** @return StatusSolicitacaoAutomatizada */
    abstract protected function getCurrentStatus(): StatusSolicitacaoAutomatizada;

    /** @return string */
    abstract protected function getEtapaProcesso(): string;

    /** Returns the description for the command. */
    public function getDescription(): string
    {
        return sprintf(
            'Comando de %s de solicitações automatizadas que trata solicitações com status %s',
            $this->getEtapaProcesso(),
            $this->getCurrentStatus()->value,
        );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $limit = 50;
        $offset = 0;
        $total = 0;
        $progress = new ProgressBar($output);
        $firstRun = true;
        $io->info(
            sprintf(
                'Iniciando processo de %s para solicitações automatizadas.',
                $this->getEtapaProcesso()
            )
        );
        do {
            $paginatedData = $this->solicitacaoAutomatizadaResource
                ->find(
                    $this->getCriteria($input, $output),
                    ['id' => 'ASC'],
                    $limit,
                    $limit * $offset
                );
            ++$offset;
            if ($firstRun) {
                $total = (int)$paginatedData['total'];
                $progress->setMaxSteps($total);
                if ($total) {
                    $io->info(
                        sprintf(
                            'Foram encontradas %s solicitações automatizadas.',
                            $total
                        )
                    );
                    $progress->start();
                } else {
                    $io->info(
                        'Não foram encontradas solicitações automatizadas.'
                    );
                }
            }
            $firstRun = false;
            foreach ($paginatedData['entities'] as $solicitacaoEntity) {
                try {
                    $transactionId = $this->transactionManager->begin();
                    $io->info(
                        sprintf(
                            'Verificando status da solicitação %s',
                            $solicitacaoEntity->getId(),
                        )
                    );
                    /** @var SolicitacaoAutomatizadaDTO $dto */
                    $dto = $this->solicitacaoAutomatizadaResource
                        ->getDtoForEntity(
                            $solicitacaoEntity->getId(),
                            SolicitacaoAutomatizadaDTO::class,
                            null,
                            $solicitacaoEntity
                        );
                    $this->solicitacaoAutomatizadaResource->update(
                        $solicitacaoEntity->getId(),
                        $dto,
                        $transactionId
                    );
                    $io->info(
                        sprintf(
                            'A solicitação %s foi alterada para o status %s',
                            $solicitacaoEntity->getId(),
                            $solicitacaoEntity->getStatus()->value,
                        )
                    );
                    $this->transactionManager->commit($transactionId);
                } catch (Throwable $e) {
                    $this->transactionManager->resetTransaction();
                    $message = sprintf(
                        'Erro no processo de %s da solicitaçao automatizada %s.',
                        $this->getEtapaProcesso(),
                        $solicitacaoEntity->getId(),
                    );
                    $this->logger->error(
                        $message,
                        [
                            'error' => $e,
                        ]
                    );
                    $io->newLine();
                    $io->error($message);
                }
                $progress->advance();
                $progress->display();
            }
        } while (($offset * $limit) < $total);
        if ($total) {
            $progress->finish();
        }
        $io->newLine();
        $io->success(
            sprintf(
                'Finalizando processo de %s para as solicitações automatizadas.',
                $this->getEtapaProcesso()
            )
        );

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return array
     */
    protected function getCriteria(
        InputInterface $input,
        OutputInterface $output
    ): array {
        return [
            'status' => sprintf(
                'eq:%s',
                $this->getCurrentStatus()->value
            ),
        ];
    }
}
