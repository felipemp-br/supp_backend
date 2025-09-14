<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/CriaRespostaOficioBarramentoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

/*
 * Class CriaRespostaOficioBarramentoMessageHandler
 *
 * @package SuppCore\AdministrativoBackend\Command\Barramento\MessageHandler
 */

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\CriaRespostaOficioBarramentoMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Classe responsável por realizar o processamento do job CriaRespostaOficioBarramentoMessageHandler.
 */
#[AsMessageHandler]
class CriaRespostaOficioBarramentoMessageHandler
{
    /**
     * EnviaComponentesDigitaisMessageHandler constructor.
     */
    public function __construct(
        private BarramentoLogger $logger,
        private TransactionManager $transactionManager,
        private ProcessoResource $processoResource,
        private DocumentoAvulsoResource $documentoAvulsoResource,
        private JuntadaRepository $juntadaRepository,
        private DocumentoResource $documentoResource,
        private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        private ParameterBagInterface $parameterBag,
        private VinculacaoProcessoResource $vinculacaoProcessoResource,
        private ModalidadeVinculacaoProcessoRepository $modalidadeVinculacaoProcessoRepository
    ) {
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(CriaRespostaOficioBarramentoMessage $criaRespostaOficioBarramentoMessage)
    {
        $transactionId = $this->transactionManager->begin();

        try {
            // processamento da resposta... clona, vincula, junta e responde
            $documentoAvulso = $this->documentoAvulsoResource->findOneBy([
                'documentoRemessa' => $criaRespostaOficioBarramentoMessage->getDocumentoId(),
                'documentoResposta' => null
            ]);

            if ($documentoAvulso && $documentoAvulso->getMecanismoRemessa() === 'barramento') {
                $documentoResposta = null;

                $processoSeiResposta = $this->processoResource->findOne(
                    $criaRespostaOficioBarramentoMessage->getProcessoId()
                );

                $juntadaEntityArray = $this->juntadaRepository->findJuntadaByProcesso($processoSeiResposta);

                foreach (array_reverse($juntadaEntityArray) as $juntada) {
                    // principal
                    if (is_null($documentoResposta)) {
                        $documentoResposta = $this->documentoResource->clonar(
                            $juntada->getDocumento()->getId(),
                            null,
                            $transactionId
                        );
                        continue;
                    }
                    // precisam ser clonados e vinculados
                    $documentoVinculado = $this->documentoResource->clonar(
                        $juntada->getDocumento()->getId(),
                        null,
                        $transactionId
                    );
                    $modalidadeVinculacaoDocumento = $this->modalidadeVinculacaoDocumentoRepository->findOneBy(
                        ['valor' => $this->parameterBag->get(
                            'constantes.entidades.modalidade_vinculacao_documento.const_1'
                        )]
                    );

                    $vinculacaoDocumentoDTO = new VinculacaoDocumento();
                    $vinculacaoDocumentoDTO->setDocumento($documentoResposta);
                    $vinculacaoDocumentoDTO->setDocumentoVinculado($documentoVinculado);
                    $vinculacaoDocumentoDTO->setModalidadeVinculacaoDocumento($modalidadeVinculacaoDocumento);

                    $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);
                }

                $documentoAvulsoDTO = new DocumentoAvulso();
                $documentoAvulsoDTO->setDocumentoResposta($documentoResposta);

                $this->documentoAvulsoResource->responder(
                    $documentoAvulso->getId(),
                    $documentoAvulsoDTO,
                    $transactionId
                );

//                $vinculacaoProcessoDTO = (new VinculacaoProcesso())
//                    ->setProcesso($documentoAvulso->getProcesso())
//                    ->setProcessoVinculado($processoSeiResposta)
//                    ->setModalidadeVinculacaoProcesso(
//                        $this->modalidadeVinculacaoProcessoRepository
//                            ->findOneBy(
//                                [
//                                    'valor' => $this->parameterBag->get(
//                                        'constantes.entidades.modalidade_vinculacao_processo.const_1'
//                                    ),
//                                ]
//                            )
//                    );
//
//                $this->vinculacaoProcessoResource->create($vinculacaoProcessoDTO, $transactionId);

                $this->transactionManager->commit($transactionId);
            }
        } catch (Throwable $e) {
            // Rollback Transaction
            $this->logger->critical("Falha no CriaRespostaOficioBarramentoMessageHandler: {$e->getMessage()}");
            $this->transactionManager->clearManager();
            throw $e;
        }
    }
}
