<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/EnviaDocumentoAvulsoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

use DateInterval;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaDocumentoAvulsoMessage;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaDocumentoAvulso;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class EnviaDocumentoAvulsoMessageHandler.
 */
#[AsMessageHandler]
class EnviaDocumentoAvulsoMessageHandler
{
    private BarramentoLogger $logger;

    private BarramentoEnviaDocumentoAvulso $barramentoEnviaDocumentoAvulso;

    private TransactionManager $transactionManager;

    private DocumentoAvulsoResource $documentoAvulsoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private ModalidadeNotificacaoResource $modalidadeNotificacaoResource;

    private NotificacaoResource $notificacaoResource;

    /**
     * EnviaDocumentoAvulsoMessageHandler constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        BarramentoEnviaDocumentoAvulso $barramentoEnviaDocumentoAvulso,
        TransactionManager $transactionManager,
        DocumentoAvulsoResource $documentoAvulsoResource,
        StatusBarramentoResource $statusBarramentoResource,
        ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        NotificacaoResource $notificacaoResource
    ) {
        $this->logger = $logger;
        $this->barramentoEnviaDocumentoAvulso = $barramentoEnviaDocumentoAvulso;
        $this->transactionManager = $transactionManager;
        $this->documentoAvulsoResource = $documentoAvulsoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->modalidadeNotificacaoResource = $modalidadeNotificacaoResource;
        $this->notificacaoResource = $notificacaoResource;
    }

    /**
     * @throws Exception
     */
    public function __invoke(EnviaDocumentoAvulsoMessage $enviaDocumentoAvulsoMessage)
    {
        try {
            $transactionId = $this->transactionManager->begin();

            $this->barramentoEnviaDocumentoAvulso->enviarDocumento(
                $enviaDocumentoAvulsoMessage->getIdRepositorioDeEstruturasRemetente(),
                $enviaDocumentoAvulsoMessage->getIdEstruturaRemetente(),
                $enviaDocumentoAvulsoMessage->getIdRepositorioEstruturasDestinatario(),
                $enviaDocumentoAvulsoMessage->getIdEstruturaDestinatario(),
                $enviaDocumentoAvulsoMessage->getDocumentoAvulsoId(),
                $transactionId
            );

            $this->transactionManager->commit($transactionId);
        } catch (Exception $e) {
            //cria uma nova transacao apenas para o catch
            $this->logger->critical("Falha no EnviaDocumentoAvulsoMessageHandler: {$e->getMessage()}");
            $transactionId = $this->transactionManager->getCurrentTransactionId();
            if ($transactionId) {
                $this->transactionManager->resetTransaction($transactionId);
            }
            $this->transactionManager->clearManager();

            $transactionIdCatch = $this->transactionManager->begin();
            $this->atualizaStatusBarramento(
                $enviaDocumentoAvulsoMessage->getDocumentoAvulsoId(),
                $e->getMessage(),
                $transactionIdCatch
            );
            $this->transactionManager->commit($transactionIdCatch);
            throw $e;
        }
    }

    /**
     * @param $documentoAvulsoId
     * @param $mensagem
     * @param $transactionId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function atualizaStatusBarramento($documentoAvulsoId, $mensagem, $transactionId): void
    {
        /** @var DocumentoAvulsoEntity $documentoAvulsoEntity */
        $documentoAvulsoEntity = $this->documentoAvulsoResource->findOne($documentoAvulsoId);

        // atualiza o status do barramento
        /** @var StatusBarramentoDTO $statusBarramentoDto */
        $statusBarramentoDto = new StatusBarramentoDTO();

        $statusBarramentoDto->setIdt(000);
        $statusBarramentoDto->setDocumentoAvulso($documentoAvulsoEntity);
        $statusBarramentoDto->setProcesso($documentoAvulsoEntity->getProcesso());
        $statusBarramentoDto->setMensagemErro($mensagem);
        $statusBarramentoDto->setCodSituacaoTramitacao(7);
        $statusBarramentoEntity = $this->statusBarramentoResource->create($statusBarramentoDto, $transactionId);

        if ($statusBarramentoEntity) {
            // cria notificação para o usuário
            $this->notificaUsuario($documentoAvulsoEntity, $transactionId);
        }
    }

    /**
     * @param $docAvulso
     * @param $transactionId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function notificaUsuario($docAvulso, $transactionId): void
    {
        $tempo = new DateTime();
        $tempo->add(new DateInterval('P30D'));

        /** @var DocumentoAvulsoEntity $docAvulso */
        $modalidadeNotificacaoEntity = $this->modalidadeNotificacaoResource->findOneBy(['valor' => 'SISTEMA']);

        $notificacaoDto = new Notificacao();
        $notificacaoDto->setDestinatario($docAvulso->getUsuarioRemessa());
        $notificacaoDto->setModalidadeNotificacao($modalidadeNotificacaoEntity);
        $notificacaoDto->setConteudo(
            'TRAMITAÇÃO VIA INTEGRAÇÃO/BARRAMENTO NO NUP '.$docAvulso->getProcesso()->getNUP().
            ' FALHOU! HOUVE A EXCLUSÃO DA TRAMITAÇÃO ID '.$docAvulso->getId().' DE MANEIRA AUTOMÁTICA!'
        );
        $notificacaoDto->setDataHoraExpiracao($tempo);
        $notificacaoDto->setUrgente(true);

        $this->notificacaoResource->create($notificacaoDto, $transactionId);
    }
}
