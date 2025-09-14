<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaCienciaMessage;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaComponentesDigitaisMessage;
use SuppCore\AdministrativoBackend\Barramento\Message\RecebeComponentesDigitaisMessage;
use SuppCore\AdministrativoBackend\Barramento\Message\RecebeReciboDeTramiteMessage;
use SuppCore\AdministrativoBackend\Barramento\Message\RecebeTramiteMessage;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao as ModalidadeNotificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as StatusBarramentoEntity;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Classe responsável por realizar o processamento das pendências do SUPP junto ao barramento.
 */
class BarramentoProcessamento extends AbstractBarramentoManager
{
    /**
     * Código da situação da tramitação de acordo com o definido nas constantes do AbstractBarramentoManager.
     */
    protected int $codSituacaoTramitacao;

    protected array $config;

    private MessageBusInterface $messageBus;

    private string $transactionId;

    private TramitacaoResource $tramitacaoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private NotificacaoResource $notificacaoResource;

    private ModalidadeNotificacaoResource $modalidadeNotificacaoResource;

    /**
     * BarramentoProcessamento constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        MessageBusInterface $bus,
        TramitacaoResource $tramitacaoResource,
        StatusBarramentoResource $statusBarramentoResource,
        NotificacaoResource $notificacaoResource,
        ModalidadeNotificacaoResource $modalidadeNotificacaoResource
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->logger = $logger;
        $this->client = $barramentoClient;
        $this->messageBus = $bus;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->notificacaoResource = $notificacaoResource;
        $this->modalidadeNotificacaoResource = $modalidadeNotificacaoResource;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
    }

    /**
     * Processa todas as pendências listadas no barramento.
     *
     * @throws ORMException
     * @throws Exception
     */
    public function processaPendencias(string $transactionId): bool
    {
        $this->transactionId = $transactionId;

        if ($this->getMensagemErro()) {
            $this->logger->critical('Falha no processamento. Erro de conexão com o barramento.');

            return false;
        }

        $responsePendencias = $this->client->listarPendencias();

        if (!isset($responsePendencias->listaDePendencias)) {
            $this->logger->critical('Falha no processamento. Não foi possível listar as pendências do barramento.');

            return false;
        }

        if (!isset($responsePendencias->listaDePendencias->IDT)) {
            $this->logger->info('Não há pendências para serem processadas.');

            return false;
        }

        if (!is_array($responsePendencias->listaDePendencias->IDT)) {
            $responsePendencias->listaDePendencias->IDT = [$responsePendencias->listaDePendencias->IDT];
        }

        foreach ($responsePendencias->listaDePendencias->IDT as $pendencia) {
            try {
                $this->processaPendencia($pendencia);
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }

        /**
         * Após processar todas as pendencias, não podem haver tramitacoes com status 0, a não ser em caso de erro.
         */
        /** @var TramitacaoEntity[] $tramitacoesErro */
        $tramitacoesErro = $this->statusBarramentoResource->getRepository()->findPendentesBarramentoComErro();

        foreach ($tramitacoesErro as $tramitacao) {
            $this->cancelaTramitacaoComNotificacao($tramitacao->getId());
        }

        return true;
    }

    /**
     * Processa pendência listada no barramento chamando seus processamentos em backgroud.
     *
     * @throws Exception
     */
    private function processaPendencia(stdClass $pendencia): void
    {
        $idt = $pendencia->_;
        $this->codSituacaoTramitacao = $pendencia->status;

        $this->logger->info(
            "Processando pendência: IDT $idt com status ".
            "[$this->codSituacaoTramitacao - {$this->getDescricaoSituacaoTramite($this->codSituacaoTramitacao)}]"
        );

        $message = $this->getObjetoMensagem($idt);

        if ($message) {
            $message->setIdt($idt);
            $this->messageBus->dispatch($message);
        }

        $this->atualizaStatusTramitacao($idt);
    }

    /**
     * @return bool|EnviaCienciaMessage|EnviaComponentesDigitaisMessage|RecebeComponentesDigitaisMessage|RecebeReciboDeTramiteMessage|RecebeTramiteMessage
     *
     * @throws Exception
     */
    private function getObjetoMensagem(int $idt)
    {
        switch ($this->codSituacaoTramitacao) {
            case AbstractBarramentoManager::SIT_AGUARDA_ENVIO_PROC_DOC:
                // Envia componentes digitais - '~jobEnviaComponentesDigitais'
                return new EnviaComponentesDigitaisMessage();
            case AbstractBarramentoManager::SIT_DOC_PROC_RECEBIDO_BARRAMENTO:
                // Recebe tramite (Processo ou Documento Avulso) - '~jobRecebeTramite',
                return new RecebeTramiteMessage();
            case AbstractBarramentoManager::SIT_META_DADOS_RECEBIDO_DESTINATARIO:
                // Recebe componentes digitais - '~jobRecebeComponentesDigitais'
                return new RecebeComponentesDigitaisMessage();
            case AbstractBarramentoManager::SIT_DOC_PROC_RECEBIDO_DESTINATARIO:
                // Envia recibo de trâmite - '~jobRecebeComponentesDigitais'
                return new RecebeComponentesDigitaisMessage();
            case AbstractBarramentoManager::SIT_RECIBO_CONCLUSAO_RECEBIDO_BARRAMENTO:
                // Recebe recibo de trâmite - '~jobRecebeReciboDeTramite'
                return new RecebeReciboDeTramiteMessage();
            case AbstractBarramentoManager::SIT_AGUARDANDO_CIENCIA:
                // Envia ciência - '~jobEnviaCiencia'
                return new EnviaCienciaMessage();
            default:
                $this->logger->info(
                    "Não há ações disponíveis para o IDT $idt com status:".
                    " [$this->codSituacaoTramitacao - 
                    {$this->getDescricaoSituacaoTramite($this->codSituacaoTramitacao)}]"
                );

                return false;
                break;
        }
    }

    /**
     * Atualiza o status das tramitações.
     *
     * @throws Exception
     */
    private function atualizaStatusTramitacao(int $idt): void
    {
        /*
         * Para os $this->codSituacaoTramitacao:
         * SIT_RECIBO_CONCLUSAO_RECEBIDO_REMETENTE (6),
         * SIT_CANCELADO (7)
         * SIT_RECUSADO_DESTINATARIO (9)
         * Não há ações para realizar.
         */

        /** @var StatusBarramentoEntity $statusBarramentoEntity */
        $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(
            ['idt' => $idt]
        );

        //status de tramite não encontrado
        if (!$statusBarramentoEntity) {
            return;
        }

        /** @var StatusBarramentoDTO $statusBarramentoDTO */
        $statusBarramentoDTO = $this->statusBarramentoResource->getDtoForEntity(
            $statusBarramentoEntity->getId(),
            StatusBarramentoDTO::class
        );

        $statusBarramentoDTO->setCodSituacaoTramitacao($this->codSituacaoTramitacao);
        $this->statusBarramentoResource->update(
            $statusBarramentoEntity->getId(),
            $statusBarramentoDTO,
            $this->transactionId
        );

        if ($statusBarramentoDTO->getTramitacao()) {
            if ((AbstractBarramentoManager::SIT_CANCELADO == $this->codSituacaoTramitacao ||
                 AbstractBarramentoManager::SIT_RECUSADO_DESTINATARIO == $this->codSituacaoTramitacao)
            ) {
                $this->cancelaTramitacaoComNotificacao($statusBarramentoDTO->getTramitacao()->getId());
            }
        }
    }

    /**
     * Apaga uma tramitação com erro e notifica o usuário.
     *
     * @throws ORMException
     * @throws Exception
     */
    private function cancelaTramitacaoComNotificacao(int $tramitacao_id): void
    {
        /** @var TramitacaoEntity $tramitacaoEntity */
        $tramitacaoEntity = $this->tramitacaoResource->findOne($tramitacao_id);

        if ($tramitacaoEntity->getCriadoPor()) {
            $tempo = new DateTime();
            $tempo->add(new DateInterval('P30D'));

            /** @var ModalidadeNotificacaoEntity $modalidadeNotificacaoEntity */
            $modalidadeNotificacaoEntity = $this->modalidadeNotificacaoResource->findOneBy(['valor' => 'SISTEMA']);

            /** @var NotificacaoDTO $notificacaoDto */
            //$notificacaoDto = $this->notificacaoResource->getDtoClass();
            $notificacaoDto = new NotificacaoDTO();
            $notificacaoDto->setRemetente($tramitacaoEntity->getCriadoPor());
            $notificacaoDto->setModalidadeNotificacao($modalidadeNotificacaoEntity);
            $notificacaoDto->setConteudo(
                'TRAMITAÇÃO VIA INTEGRAÇÃO/BARRAMENTO NO NUP '.$tramitacaoEntity->getProcesso()->getNUP().
                ' FALHOU! HOUVE A EXCLUSÃO DA TRAMITAÇÃO ID '.$tramitacaoEntity->getId().' DE MANEIRA AUTOMÁTICA!'
            );
            $notificacaoDto->setDataHoraExpiracao($tempo);
            $notificacaoDto->setUrgente(true);

            $this->notificacaoResource->create($notificacaoDto, $this->transactionId);
        }
    }
}
