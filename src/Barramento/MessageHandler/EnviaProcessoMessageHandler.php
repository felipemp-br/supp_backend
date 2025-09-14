<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/EnviaProcessoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource as TramitacaoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaProcessoMessage;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaProcesso;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class EnviaProcessoMessageHandler.
 */
#[AsMessageHandler]
class EnviaProcessoMessageHandler
{
    private BarramentoLogger $logger;

    private BarramentoEnviaProcesso $barramentoEnvioProcesso;

    private TransactionManager $transactionManager;

    private TramitacaoResource $tramitacaoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private ModalidadeNotificacaoResource $modalidadeNotificacaoResource;

    private NotificacaoResource $notificacaoResource;

    /**
     * EnviaProcessoMessageHandler constructor.
     * @param BarramentoLogger $logger
     * @param BarramentoEnviaProcesso $enviaProcesso
     * @param TransactionManager $transactionManager
     * @param TramitacaoResource $tramitacaoResource
     * @param StatusBarramentoResource $statusBarramentoResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param NotificacaoResource $notificacaoResource
     */
    public function __construct(
        BarramentoLogger $logger,
        BarramentoEnviaProcesso $enviaProcesso,
        TransactionManager $transactionManager,
        TramitacaoResource $tramitacaoResource,
        StatusBarramentoResource $statusBarramentoResource,
        ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        NotificacaoResource $notificacaoResource
    ) {
        $this->logger = $logger;
        $this->barramentoEnvioProcesso = $enviaProcesso;
        $this->transactionManager = $transactionManager;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->modalidadeNotificacaoResource = $modalidadeNotificacaoResource;
        $this->notificacaoResource = $notificacaoResource;
    }

    /**
     * Executa job para enviar processo ao barramento.
     */
    public function __invoke(EnviaProcessoMessage $enviaProcessoMessage)
    {
        $idRepositorioDeEstruturasRemetente = $enviaProcessoMessage->getIdRepositorioDeEstruturasRemetente();
        $idEstruturaRemetente = $enviaProcessoMessage->getIdEstruturaRemetente();
        $idRepositorioEstruturasDestinatario = $enviaProcessoMessage->getIdRepositorioEstruturasDestinatario();
        $idEstruturaDestinatario = $enviaProcessoMessage->getIdEstruturaDestinatario();
        $tramitacaoUuId = $enviaProcessoMessage->getTramitacaoUuid();

        $transactionId = $this->transactionManager->begin();

        try {
            $this->barramentoEnvioProcesso->enviarProcesso(
                $idRepositorioDeEstruturasRemetente,
                $idEstruturaRemetente,
                $idRepositorioEstruturasDestinatario,
                $idEstruturaDestinatario,
                $tramitacaoUuId,
                $transactionId
            );
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $e) {
            $this->logger->critical("Falha no EnviaProcessoMessageHandler: {$e->getMessage()}");
            $transactionId = $this->transactionManager->getCurrentTransactionId();
            if ($transactionId) {
                $this->transactionManager->resetTransaction($transactionId);
            }
            $this->transactionManager->clearManager();

            //cria uma nova transacao apenas para o catch
            $transactionIdCatch = $this->transactionManager->begin();
            $this->atualizaStatusBarramento(
                $tramitacaoUuId,
                $e->getMessage(),
                $transactionIdCatch
            );
            $this->transactionManager->commit($transactionIdCatch);
            throw $e;
        }
    }

    /**
     * @param $tramitacaoUuId
     * @param $mensagem
     * @param $transactionId
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function atualizaStatusBarramento(
        $tramitacaoUuId,
        $mensagem,
        $transactionId
    ): void {
        /** @var TramitacaoEntity $tramitacao */
        $tramitacao = $this->tramitacaoResource->findOneBy(['uuid' => $tramitacaoUuId]);

        if ($tramitacao) {
            // atualiza o status do barramento
            $statusBarramentoDto = new StatusBarramentoDTO();

            $statusBarramentoDto->setIdt(000);
            $statusBarramentoDto->setTramitacao($tramitacao);
            $statusBarramentoDto->setProcesso($tramitacao->getProcesso());
            if (!$mensagem) {
                $statusBarramentoDto->setMensagemErro('Serviço do barramento SEI indisponível');
            } else {
                $statusBarramentoDto->setMensagemErro($mensagem);
            }

            $statusBarramentoDto->setCodSituacaoTramitacao(7);
            $statusBarramentoEntity = $this->statusBarramentoResource->create($statusBarramentoDto, $transactionId);

            if ($statusBarramentoEntity) {
                // recebe a remessa
                $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                    $tramitacao->getId(),
                    Tramitacao::class
                );
                $tramitacaoDto->setDataHoraRecebimento(new DateTime());
                $tramitacaoEntity = $this->tramitacaoResource->update(
                    $tramitacao->getId(),
                    $tramitacaoDto,
                    $transactionId
                );

                // cria notificação para o usuário
                $this->notificaUsuario($tramitacaoEntity, $transactionId);
            }
        }
    }

    /**
     * @param $tramitacaoEntity
     * @param $transactionId
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function notificaUsuario(
        $tramitacaoEntity,
        $transactionId
    ): void {
        $tempo = new DateTime();
        $tempo->add(new DateInterval('P30D'));

        /** @var ModalidadeNotificacao $modalidadeNotificacaoEntity */
        $modalidadeNotificacaoEntity = $this->modalidadeNotificacaoResource->findOneBy(['valor' => 'SISTEMA']);

        //$notificacaoDto = $this->notificacaoResource->getDtoClass();
        $notificacaoDto = new Notificacao();
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
