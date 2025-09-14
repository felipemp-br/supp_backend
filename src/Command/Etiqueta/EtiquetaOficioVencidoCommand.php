<?php

declare(strict_types=1);
/**
 * /src/Command/User/EtiquetaOficioVencidoCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Etiqueta;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Repository\DocumentoAvulsoRepository;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class EtiquetaOficioVencidoCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:administrativo:etiqueta:oficiovencido', description: 'Command para etiquetar oficios vencidos')]
class EtiquetaOficioVencidoCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * EtiquetaOficioVencidoCommand constructor.
     *
     * @param LoggerInterface            $logger
     * @param VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
     * @param EtiquetaRepository         $etiquetaRepository
     * @param DocumentoAvulsoRepository  $documentoAvulsoRepository
     * @param TransactionManager         $transactionManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private readonly EtiquetaRepository $etiquetaRepository,
        private readonly DocumentoAvulsoRepository $documentoAvulsoRepository,
        private readonly TransactionManager $transactionManager
    ) {
        parent::__construct();
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
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        // Busca os oficios com dataHoraFinalPrazo no passado
        $documentosAvulsos = $this->documentoAvulsoRepository->findDocumentoAvulsoVencido();
        $outputCode = Command::SUCCESS;
        $totalOficios = 0;

        try {
            if ($documentosAvulsos) {
                $transactionId = $this->transactionManager->begin();

                foreach ($documentosAvulsos as $oficio) {
                    $etiquetaTarefa = $this->vinculacaoEtiquetaResource->findOneBy(
                        [
                            'label' => 'OFÍCIO VENCIDO',
                            'tarefa' => $oficio->getTarefaOrigem(),
                        ]
                    );

                    // etiquetar apenas para tarefas não concluidas e que não foram etiquetadas
                    if (!$oficio->getTarefaOrigem()->getDataHoraConclusaoPrazo()
                        && !$etiquetaTarefa
                    ) {
                        $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
                        $vinculacaoEtiquetaDTO->setEtiqueta(
                            $this->etiquetaRepository->findOneBy(
                                [
                                    'nome' => 'OFÍCIO VENCIDO',
                                    'sistema' => true,
                                ]
                            )
                        );

                        $vinculacaoEtiquetaDTO->setTarefa($oficio->getTarefaOrigem());
                        $vinculacaoEtiquetaDTO->setObjectClass(get_class($oficio));
                        $vinculacaoEtiquetaDTO->setObjectUuid($oficio->getUuid());
                        $vinculacaoEtiquetaDTO->setLabel('OFÍCIO VENCIDO');
                        $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId, true);
                        ++$totalOficios;
                    }
                }

                $this->transactionManager->commit($transactionId);
            }
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
            throw $t;
        }

        if ($input->isInteractive()) {
            $io->success($message ?? 'Total de '.$totalOficios.' ofícios vencidos etiquetados');
        }

        return $outputCode;
    }
}
