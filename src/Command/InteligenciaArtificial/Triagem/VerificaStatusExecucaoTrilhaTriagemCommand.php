<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\InteligenciaArtificial\Triagem;

use DateTime;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata as DocumentoIAMetadataDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIAMetadataResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\DocumentoIAMetadata as DocumentoIAMetadataEntity;
use SuppCore\AdministrativoBackend\Enums\StatusExecucaoTrilhaTriagem;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * VerificaStatusExecucaoTrilhaTriagemCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:ia:triagem:verifica_status_execucao_trilha_triagem',
    description: 'Verifica inconsistências nos status de execução da trilha de triagem'
)]
class VerificaStatusExecucaoTrilhaTriagemCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * Constructor.
     *
     * @param DocumentoIAMetadataResource $documentoIAMetadataResource
     * @param LoggerInterface             $logger
     * @param TransactionManager          $transactionManager
     */
    public function __construct(
        private readonly DocumentoIAMetadataResource $documentoIAMetadataResource,
        private readonly LoggerInterface $logger,
        private readonly TransactionManager $transactionManager,
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = $this->getSymfonyStyle($input, $output);
            $limit = 50;
            $offset = 0;
            $total = 0;
            $progress = new ProgressBar($output);
            $firstRun = true;
            $io->info('Iniciando verificação de status de execução de trilhas de triagem inconsistentes.');
            $dataExecucaoTrilhaTriagem = (new DateTime('-1 hour'))->format('Y-m-d\TH:i:s');
            do {
                $transactionId = $this->transactionManager->begin();
                $paginatedData = $this->documentoIAMetadataResource
                    ->find(
                        [
                            'statusExecucaoTrilhaTriagem' => sprintf(
                                'eq:%s',
                                StatusExecucaoTrilhaTriagem::INICIADA
                            ),
                            'dataExecucaoTrilhaTriagem' => sprintf(
                                'lt:%s',
                                $dataExecucaoTrilhaTriagem
                            ),
                        ],
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
                                'Foram encontradas %s trilhas de triagem inconsistentes.',
                                $total
                            )
                        );
                        $progress->start();
                    } else {
                        $io->info(
                            'Não foram encontradas trilhas de triagem inconsistentes.'
                        );
                    }
                }
                /** @var DocumentoIAMetadataEntity $entity */
                foreach ($paginatedData['entities'] as $entity) {
                    $io->info(
                        sprintf(
                            'Verificando status da solicitação %s',
                            $entity->getId(),
                        )
                    );
                    /** @var DocumentoIAMetadataDTO $dto */
                    $dto = $this->documentoIAMetadataResource
                        ->getDtoForEntity(
                            $entity->getId(),
                            DocumentoIAMetadataDTO::class,
                            null,
                            $entity
                        );
                    $dto->setStatusExecucaoTrilhaTriagem(
                        StatusExecucaoTrilhaTriagem::ERRO
                    );
                    $this->documentoIAMetadataResource->update(
                        $entity->getId(),
                        $dto,
                        $transactionId
                    );
                    $io->info(
                        sprintf(
                            'O Documento IA Metadata %s foi alterada para o status %s',
                            $entity->getId(),
                            $entity->getStatusExecucaoTrilhaTriagem()->value,
                        )
                    );
                    $progress->advance();
                    $progress->display();
                }
                $firstRun = false;
                $this->transactionManager->commit($transactionId);
            } while (($offset * $limit) < $total);
            if ($total) {
                $progress->finish();
            }
            $io->newLine();
            $io->success('Finalizanda verificação de status de execução de trilhas de triagem inconsistentes.');

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $message = sprintf(
                'Erro na verificação de status de execução de trilhas de triagem inconsistentes. Erro: %s',
                $e->getMessage()
            );
            $this->logger->error(
                $message,
                [
                    'error' => $e,
                ]
            );
            $io->newLine();
            $io->error($message);
            return Command::FAILURE;
        }
    }
}
