<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/MessageHandler/CopyProcessoDocumentosMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\MessageHandler;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\CopyProcessoDocumentosMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class CopyProcessoDocumentosMessageHandler.
 * 
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class CopyProcessoDocumentosMessageHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly ProcessoResource $processoResource,
        private readonly LoggerInterface $logger,
        private readonly NumeroUnicoDocumentoResource $numeroUnicoDocumentoResource,
        private readonly DocumentoResource $documentoResource,
        private readonly JuntadaResource $juntadaResource,
        private readonly NotificacaoResource $notificacaoResource,
        private readonly ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        private readonly TipoNotificacaoResource $tipoNotificacaoResource,
        private readonly ParameterBagInterface $parameterBag
    ) {}

    /**
     * @param CopyProcessoDocumentosMessage $message
     */
    public function __invoke(CopyProcessoDocumentosMessage $message)
    {
        
        $qtdDocumentos = 0;
        try {
            $processoEntity = $this->processoResource->getRepository()->findOneBy(['uuid' => $message->getUuid()]);
            if (!$processoEntity || ($processoEntity && !$processoEntity->getProcessoOrigem())) {
                return;
            }
            
            $transactionId = $this->transactionManager->begin();
            $volumeNovo = $processoEntity->getVolumes()->first();

            /* @var Volume */
            foreach ($processoEntity->getProcessoOrigem()->getVolumes() as $volume) {
                $qtdDocumentos += $volume->getJuntadas()->filter(fn($juntada) => $juntada->getAtivo())->count();
            }
            
            /* @var Volume */
            foreach ($processoEntity->getProcessoOrigem()->getVolumes() as $volume) {
                /** @var JuntadaEntity $juntada */
                foreach ($volume->getJuntadas() as $juntada) {
                    if ($juntada->getAtivo()) {
                        // copia o documento
                        $documentoDTO = new DocumentoDTO();
                        $documentoDTO->setSetorOrigem($juntada->getDocumento()->getSetorOrigem());
                        $documentoDTO->setTipoDocumento($juntada->getDocumento()->getTipoDocumento());
                        $documentoDTO->setProcessoOrigem($processoEntity);

                        $numeroUnicoDocumento = $this->numeroUnicoDocumentoResource->generate(
                            $documentoDTO->getSetorOrigem(),
                            $documentoDTO->getTipoDocumento()
                        );

                        $documentoDTO->setNumeroUnicoDocumento($numeroUnicoDocumento);

                        $documentoNovo = $this->documentoResource->create($documentoDTO, $transactionId);

                        /** @var DocumentoDTO $documentoDTO */
                        $documentoNovo = $this->documentoResource->clonar(
                            $juntada->getDocumento()->getId(),
                            $documentoNovo,
                            $transactionId
                        );

                        // nova juntada
                        $juntadaDTO = new JuntadaDTO();
                        $juntadaDTO->setDocumento($documentoNovo);
                        $juntadaDTO->setVolume($volumeNovo);
                        $juntadaDTO->setDescricao($juntada->getDescricao());
                        $this->juntadaResource->create($juntadaDTO, $transactionId);
                    }
                }
            }


            $modalidadeNotificacao = $this->modalidadeNotificacaoResource
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);
            $tipoNotificacao = $this->tipoNotificacaoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);

            if($qtdDocumentos) {
                $msg = $qtdDocumentos . ' DOCUMENTO(S) COPIADO(S) PARA O NUP ['.$processoEntity->getNUP().']';
            } else {
                $msg = 'NENHUM DOCUMENTO COPIADO PARA O NUP ['.$processoEntity->getNUP().']';
            }

            $notificacaoDTO = (new NotificacaoDTO())
                ->setDestinatario($processoEntity->getCriadoPor())
                ->setModalidadeNotificacao($modalidadeNotificacao)
                ->setConteudo($msg)
                ->setTipoNotificacao($tipoNotificacao)
                ->setContexto(json_encode(
                    [
                        'id' => $processoEntity->getId(),
                    ]
                ));

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);

            $this->transactionManager->commit($transactionId);

        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
            $this->transactionManager->resetTransaction();

            $modalidadeNotificacao = $this->modalidadeNotificacaoResource
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);
            $tipoNotificacao = $this->tipoNotificacaoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);

            $msg = 'ERRO AO TENTAR COPIAR '.$qtdDocumentos.' DOCUMENTO(S) PARA O NUP ['.$processoEntity->getNUP().']';

            $transactionId = $this->transactionManager->begin();
            $notificacaoDTO = (new NotificacaoDTO())
                ->setDestinatario($processoEntity->getCriadoPor())
                ->setModalidadeNotificacao($modalidadeNotificacao)
                ->setConteudo($msg)
                ->setTipoNotificacao($tipoNotificacao)
                ->setContexto(json_encode(
                    [
                        'id' => $processoEntity->getId(),
                    ]
                ));

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            $this->transactionManager->commit($transactionId);
        }
    }
}
