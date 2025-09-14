<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/CriaTarefaBarramentoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

/*
 * Class CriaTarefaBarramentoMessageHandler
 *
 * @package SuppCore\AdministrativoBackend\Command\Barramento\MessageHandler
 */

use DateInterval;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Barramento\Message\CriaTarefaBarramentoMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\EspecieTarefaRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Classe responsável por realizar o processamento do job CriaTarefaBarramentoMessageHandler.
 */
#[AsMessageHandler]
class CriaTarefaBarramentoMessageHandler
{
    /**
     * EnviaComponentesDigitaisMessageHandler constructor.
     */
    public function __construct(
        private BarramentoLogger $logger,
        private TransactionManager $transactionManager,
        private SetorResource $setorResource,
        private ProcessoResource $processoResource,
        private EspecieTarefaRepository $especieTarefaRepository,
        private TarefaResource $tarefaResource
    ) {
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(CriaTarefaBarramentoMessage $criaTarefaBarramentoMessage)
    {
        $transactionId = $this->transactionManager->begin();

        try {
            $this->transactionManager->addContext(
                new Context('criacaoTarefaBarramento', true),
                $transactionId
            );
            $inicioPrazo = new DateTime();
            $finalPrazo = new DateTime();
            $finalPrazo = $finalPrazo->add(new DateInterval('P5D'));

            $tarefaDto = new TarefaDTO();
            $tarefaDto->setProcesso($this->processoResource->findOneBy(
                ['uuid' => $criaTarefaBarramentoMessage->getProcessoUuid()]
            ));
            $tarefaDto->setSetorResponsavel(
                $this->setorResource->findOne($criaTarefaBarramentoMessage->getSetorResponsavelId())
            );
            $tarefaDto->setDistribuicaoAutomatica(true);

            /** @var EspecieTarefaEntity $especieTarefaEntity */
            $especieTarefaEntity = $this->especieTarefaRepository->findByNomeAndGenero(
                'ANALISAR DEMANDAS',
                'ADMINISTRATIVO'
            );
            $tarefaDto->setEspecieTarefa($especieTarefaEntity);
            $tarefaDto->setDataHoraInicioPrazo($inicioPrazo);
            $tarefaDto->setDataHoraFinalPrazo($finalPrazo);

            /* @var TarefaEntity $tarefaEntity */
            $this->tarefaResource->create($tarefaDto, $transactionId);
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $e) {
            // Rollback Transaction
            $this->logger->critical("Falha no CriaTarefaBarramentoMessageHandler: {$e->getMessage()}");
            $this->transactionManager->clearManager();
            throw $e;
        }
    }
}
