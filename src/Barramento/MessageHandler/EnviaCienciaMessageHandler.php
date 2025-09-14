<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/EnviaCienciaMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

/*
 * Class EnviaCienciaMessageHandler
 *
 * @package SuppCore\AdministrativoBackend\Command\Barramento\MessageHandler
 */

use DateInterval;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao as ModalidadeNotificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaCienciaMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoClient;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class EnviaCienciaMessageHandler.
 */
#[AsMessageHandler]
class EnviaCienciaMessageHandler
{
    private BarramentoLogger $logger;

    private BarramentoClient $barramentoClient;

    private TransactionManager $transactionManager;

    private StatusBarramentoResource $statusBarramentoResource;

    private TramitacaoResource $tramitacaoResource;
    
    private ModalidadeNotificacaoResource $modalidadeNotificacaoResource;
    
    private NotificacaoResource $notificacaoResource;

    /**
     * EnviaCienciaMessageHandler constructor.
     * @param BarramentoLogger $logger
     * @param BarramentoClient $barramentoClient
     * @param TransactionManager $transactionManager
     * @param StatusBarramentoResource $statusBarramentoResource
     * @param TramitacaoResource $tramitacaoResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param NotificacaoResource $notificacaoResource
     */
    public function __construct(
        BarramentoLogger $logger,
        BarramentoClient $barramentoClient,
        TransactionManager $transactionManager,
        StatusBarramentoResource $statusBarramentoResource,
        TramitacaoResource $tramitacaoResource,
        ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        NotificacaoResource $notificacaoResource
    ) {
        $this->logger = $logger;
        $this->barramentoClient = $barramentoClient;
        $this->transactionManager = $transactionManager;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->modalidadeNotificacaoResource = $modalidadeNotificacaoResource;
        $this->notificacaoResource = $notificacaoResource;
    }

    /**
     * @param EnviaCienciaMessage $enviaCienciaMessage
     *
     * @throws Exception
     */
    public function __invoke(EnviaCienciaMessage $enviaCienciaMessage)
    {
        $idt = $enviaCienciaMessage->getIdt();
        $transactionId = $this->transactionManager->begin();

        try {
            $consultarTramitesResponse = $this->barramentoClient->consultarTramites($idt);

            if ($consultarTramitesResponse &&
                isset($consultarTramitesResponse->tramitesEncontrados->tramite->justificativaDaRecusa)) {

                /** @var StatusBarramento $statusBarramentoEntity */
                $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(['idt' => $idt]);

                if ($statusBarramentoEntity) {
                    // atualiza o status do barramento
                    $this->atualizaStatusBarramento(
                        $transactionId,
                        $statusBarramentoEntity,
                        $consultarTramitesResponse
                    );

                    if ($statusBarramentoEntity->getTramitacao()) {
                        // recebe a remessa
                        $tramitacaoEntity = $this->sincronizaTramitacao(
                            $transactionId,
                            $statusBarramentoEntity->getProcesso()
                        );
                    }

                    if ($tramitacaoEntity) {
                        // cria notificação para o usuário
                        $this->notificaUsuario($transactionId, $tramitacaoEntity);
                    }
                }
            }

            // envia ciencia para o barramento
            $this->barramentoClient->cienciaRecusa($idt);

            $this->transactionManager->commit($transactionId);
        } catch (Throwable $e) {
            // Rollback Transaction
            $this->logger->critical("Falha no EnviaCienciaMessageHandler: {$e->getMessage()}");
            $transactionId = $this->transactionManager->getCurrentTransactionId();
            if ($transactionId) {
                $this->transactionManager->resetTransaction($transactionId);
            }
            $this->transactionManager->clearManager();
            throw $e;
        }
    }

    /**
     * @param $transactionId
     * @param $statusBarramentoEntity
     * @param $consultarTramitesResponse
     */
    private function atualizaStatusBarramento(
        $transactionId,
        $statusBarramentoEntity,
        $consultarTramitesResponse
    ): StatusBarramento|null {
        if ($statusBarramentoEntity) {
            /** @var StatusBarramentoDTO $statusBarramentoDto */
            $statusBarramentoDto = $this->statusBarramentoResource->getDtoForEntity(
                $statusBarramentoEntity->getId(),
                StatusBarramentoDTO::class
            );

            $statusBarramentoDto->setMensagemErro(
                $consultarTramitesResponse->tramitesEncontrados->tramite->justificativaDaRecusa
            );
            $statusBarramentoDto->setCodigoErro(
                (int)$consultarTramitesResponse->
                tramitesEncontrados->tramite->motivoDaRecusa
            );

            $statusBarramentoDto->setCodSituacaoTramitacao(7);

            $statusBarramentoEntity = $this->statusBarramentoResource->update(
                $statusBarramentoEntity->getId(),
                $statusBarramentoDto,
                $transactionId
            );

            return $statusBarramentoEntity;
        }
        return $statusBarramentoEntity;
    }

    /**
     * @param ProcessoEntity $processoEntity
     * @return Tramitacao|null|bool
     */
    private function sincronizaTramitacao($transactionId, ProcessoEntity $processoEntity): Tramitacao|null|bool
    {
        /** @var TramitacaoEntity $tramitacaoEntity */
        $tramitacaoEntity = $this->tramitacaoResource->getRepository()->findPendenteExternaProcesso(
            $processoEntity->getId()
        );

        if ($tramitacaoEntity) {
            /** @var TramitacaoDTO $tramitacaoDto */
            $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                $tramitacaoEntity->getId(),
                TramitacaoDTO::class
            );
            $tramitacaoDto->setDataHoraRecebimento(new DateTime());
            $tramitacaoEntity = $this->tramitacaoResource->update(
                $tramitacaoEntity->getId(),
                $tramitacaoDto,
                $transactionId
            );
            return $tramitacaoEntity;
        }
        return $tramitacaoEntity;
    }

    /**
     * @param $transactionId
     * @param $tramitacaoEntity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function notificaUsuario(
        $transactionId,
        $tramitacaoEntity
    ): void {
        $tempo = new DateTime();
        $tempo->add(new DateInterval('P30D'));

        /** @var ModalidadeNotificacaoEntity $modalidadeNotificacaoEntity */
        $modalidadeNotificacaoEntity = $this->modalidadeNotificacaoResource->findOneBy(['valor' => 'SISTEMA']);

        //$notificacaoDto = $this->notificacaoResource->getDtoClass();
        $notificacaoDto = new NotificacaoDTO();
        $notificacaoDto->setDestinatario($tramitacaoEntity->getCriadoPor());
        $notificacaoDto->setModalidadeNotificacao($modalidadeNotificacaoEntity);
        $notificacaoDto->setConteudo(
            'TRAMITAÇÃO VIA INTEGRAÇÃO/BARRAMENTO NO NUP '.$tramitacaoEntity->getProcesso()->getNUP().
            ' FALHOU! HOUVE A EXCLUSÃO DA TRAMITAÇÃO ID '.$tramitacaoEntity->getId().' DE MANEIRA AUTOMÁTICA!'
        );
        $notificacaoDto->setDataHoraExpiracao($tempo);
        $notificacaoDto->setUrgente(true);

        $this->notificacaoResource->create($notificacaoDto, $transactionId);
    }
}
