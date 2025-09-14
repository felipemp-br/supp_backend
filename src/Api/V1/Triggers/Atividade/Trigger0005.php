<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Atividade/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Caso informados documentos no request para responder o ofício, o ofício será respondido!
 *
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        private DocumentoResource $documentoResource,
        private DocumentoAvulsoResource $documentoAvulsoResource,
        private JuntadaResource $juntadaResource,
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private ParameterBagInterface $parameterBag,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Atividade|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ('responder_oficio' === $restDto->getDestinacaoMinutas()) {
            $this->transactionManager->addContext(
                new Context(
                    'respostaDocumentoAvulso',
                    true
                ),
                $transactionId
            );

            /** @var Atividade $atividade */
            $atividade = $restDto;

            $documentosId = [];
            foreach ($atividade->getDocumentos() as $documento) {
                $documentosId[] = $documento->getId();
            }

            foreach ($atividade->getTarefa()->getMinutas() as $documento) {
                if (!$documento->getJuntadaAtual()
                    && (
                        $atividade->getEncerraTarefa()
                        || in_array(
                            $documento->getId(),
                            $documentosId
                        )
                    )
                ) {
                    $juntadaDTO = new Juntada();
                    $juntadaDTO->setAtividade($entity);
                    $juntadaDTO->setDocumento($documento);
                    $juntadaDTO->setDescricao($entity->getEspecieAtividade()->getNome());

                    $this->juntadaResource->create($juntadaDTO, $transactionId);
                }
            }

            // processamento da resposta... clona, vincula, junta e responde
            $documentoAvulso = $restDto->getTarefa()->getProcesso()->getDocumentoAvulsoOrigem();

            $documentoResposta = null;

            /** @var Documento $documento */
            foreach ($atividade->getDocumentos() as $documento) {
                // principal
                if (is_null($documentoResposta)) {
                    $documentoResposta = $this->documentoResource->clonar(
                        $documento->getId(),
                        null,
                        $transactionId
                    );
                    continue;
                }
                // precisam ser clonados e vinculados
                $documentoVinculado = $this->documentoResource->clonar(
                    $documento->getId(),
                    null,
                    $transactionId,
                    null,
                    false
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

                /** @var VinculacaoDocumentoEntity $vinc */
                foreach ($documento->getVinculacoesDocumentos()->toArray() as $vinc) {
                    $docVinc = $this->documentoResource->clonar(
                        $vinc->getDocumentoVinculado()->getId(),
                        null,
                        $transactionId,
                        null,
                        false
                    );

                    $vinculacaoDocumentoDTO = new VinculacaoDocumento();
                    $vinculacaoDocumentoDTO->setDocumento($documentoResposta);
                    $vinculacaoDocumentoDTO->setDocumentoVinculado($docVinc);
                    $vinculacaoDocumentoDTO->setModalidadeVinculacaoDocumento($modalidadeVinculacaoDocumento);

                    $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);
                }
            }

            $documentoAvulsoDTO = new DocumentoAvulso();
            $documentoAvulsoDTO->setDocumentoResposta($documentoResposta);

            $this->documentoAvulsoResource->responder($documentoAvulso->getId(), $documentoAvulsoDTO, $transactionId);

            $this->transactionManager->removeContext('respostaDocumentoAvulso', $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
