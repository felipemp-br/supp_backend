<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Caso não informado um documento para o componente digital, ele será automaticamente criado como minuta!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * @param DocumentoResource $documentoResource
     * @param TipoDocumentoResource $tipoDocumentoResource
     * @param NumeroUnicoDocumentoResource $numeroUnicoDocumentoResource
     * @param DocumentoAvulsoResource $documentoAvulsoResource
     * @param JuntadaResource $juntadaResource
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ParameterBagInterface $parameterBag
     * @param TokenStorageInterface $tokenStorage
     * @param LotacaoRepository $lotacaoRepository
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(
        private DocumentoResource $documentoResource,
        private TipoDocumentoResource $tipoDocumentoResource,
        private NumeroUnicoDocumentoResource $numeroUnicoDocumentoResource,
        private DocumentoAvulsoResource $documentoAvulsoResource,
        private JuntadaResource $juntadaResource,
        private AuthorizationCheckerInterface $authorizationChecker,
        private ParameterBagInterface $parameterBag,
        private TokenStorageInterface $tokenStorage,
        private LotacaoRepository $lotacaoRepository,
        private ComponenteDigitalResource $componenteDigitalResource,
        private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
                'beforeAprovar',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(
        ComponenteDigital|RestDtoInterface|null $restDto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        if (!$restDto->getDocumento()) {
            if ($restDto->getModelo()) {
                $tipoDocumento = $restDto->getModelo()->getTemplate()->getDocumento()->getTipoDocumento();
            } elseif ($restDto->getTipoDocumento()) {
                $tipoDocumento = $restDto->getTipoDocumento();
            } elseif ($restDto->getDocumentoOrigem()) {
                // pegando o mesmo tipo de documento do original
                $componenteOrigem = $restDto->getHash() ?
                $this->componenteDigitalResource->findOneBy(['hash' => $restDto->getHash()]) : null;
                if ($componenteOrigem) {
                    $tipoDocumento = $componenteOrigem->getDocumento()->getTipoDocumento();

                    if($componenteOrigem->getAssinaturas()) {
                        if ($componenteOrigem->getDocumento()->getOrigemDados()) {
                            // Removemos do contexto caso tenha origem dados
                            $this->transactionManager->removeContext('clonarAssinatura', $transactionId);
                        } else {
                            // Adicionamos o contexto caso não tenha na requisição
                            if(!$this->transactionManager->getContext('clonarAssinatura', $transactionId)) {
                                $this->transactionManager->addContext(
                                    new Context('clonarAssinatura', true),
                                    transactionId: $transactionId
                                );
                            }
                        }
                    }
                } else {
                    $tipoDocumento = $this->tipoDocumentoResource->findOneBy(['nome' => 'DESPACHO']);
                }
            } elseif ($restDto->getComponenteDigitalOrigem()) {
                $tipoDocumento = $restDto->getComponenteDigitalOrigem()->getDocumento()->getTipoDocumento();
            } else {
                $tipoDocumento = null;
                if ($restDto->getFileName()) {
                    $tipoDocumento = $this->tipoDocumentoResource->retornaTipoDocumentoByFilename(
                        $restDto->getFileName(),
                        $restDto->getExtensao()
                    );
                }

                if (!$tipoDocumento) {
                    $tipoDocumentoRepository = $this->tipoDocumentoResource->getRepository();
                    $tipoDocumento = $tipoDocumentoRepository
                        ->findOneBy([
                            'nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_1'),
                        ]);
                }
            }

            $documentoDTO = new Documento();
            $documentoDTO->setTipoDocumento($tipoDocumento);
            $documentoDTO->setProcessoOrigem($restDto->getProcessoOrigem());
            $documentoDTO->setTarefaOrigem($restDto->getTarefaOrigem());

            if ($restDto->getProcessoOrigem()) {
                $documentoDTO->setSetorOrigem($restDto->getProcessoOrigem()->getSetorAtual());
            }

            // juntada imotivada
            if (!$documentoDTO->getTarefaOrigem() && !$restDto->getDocumentoOrigem()) {
                if ($this->tokenStorage?->getToken()?->getUser()?->getColaborador()) {
                    $lotacaoPrincipal = $this->lotacaoRepository->findLotacaoPrincipal(
                        $this->tokenStorage->getToken()->getUser()->getColaborador()->getId()
                    );

                    if ($lotacaoPrincipal) {
                        $documentoDTO->setSetorOrigem($lotacaoPrincipal->getSetor());
                    }
                }
            }

            if ($documentoDTO->getTarefaOrigem()) {
                if ($this->transactionManager->getContext('documento_avulso_setor_origem', $transactionId)) {
                    $documentoDTO->setSetorOrigem(
                        $this->transactionManager->getContext(
                            'documento_avulso_setor_origem', $transactionId)->getValue());
                } else {
                    $documentoDTO->setSetorOrigem($restDto->getTarefaOrigem()->getSetorResponsavel());
                }
                $documentoDTO->setProcessoOrigem($documentoDTO->getTarefaOrigem()->getProcesso());
            }

            if ($restDto->getDocumentoAvulsoOrigem()) {
                $this->transactionManager->addContext(
                    new Context('documento_resposta_oficio', $transactionId),
                    $transactionId
                );

                $documentoDTO->setProcessoOrigem($restDto->getDocumentoAvulsoOrigem()->getProcesso());

                $documentoAvulso = $this->documentoAvulsoResource->findOne(
                    $restDto->getDocumentoAvulsoOrigem()->getId()
                );

                if ($documentoAvulso->getDataHoraResposta()) {
                    $documentoDTO->setDocumentoAvulsoComplementacaoResposta($restDto->getDocumentoAvulsoOrigem());
                }
            }

            if ($restDto->getDocumentoOrigem()) {
                $documentoDTO->setProcessoOrigem($restDto->getDocumentoOrigem()->getProcessoOrigem());
                $restDto->getDocumentoOrigem()?->getTarefaOrigem()?->getSetorResponsavel() ?
                    $documentoDTO->setSetorOrigem(
                        $restDto->getDocumentoOrigem()->getTarefaOrigem()->getSetorResponsavel()
                    ) :
                    $documentoDTO->setSetorOrigem($restDto->getDocumentoOrigem()->getSetorOrigem());
            }

            if ($restDto->getModelo() || $restDto->getComponenteDigitalOrigem() ||
                ($restDto->getDocumentoOrigem() && !$restDto->getDocumentoOrigem()->getModelo())) {
                // criação do Número Único do Documento
                $numeroUnicoDocumento = $this->numeroUnicoDocumentoResource->generate(
                    $documentoDTO->getSetorOrigem(),
                    $documentoDTO->getTipoDocumento()
                );

                $documentoDTO->setNumeroUnicoDocumento($numeroUnicoDocumento);
            }

            $documento = $this->documentoResource->create($documentoDTO, $transactionId);
            $restDto->setDocumento($documento);

            // o modelo tem anexos?
            if ($restDto->getModelo()) {
                foreach ($restDto->getModelo()->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                    /** @var Documento $documentoDTO */
                    $documentoClonado = $this->documentoResource->clonar(
                        $vinculacaoDocumento->getDocumentoVinculado()->getId(),
                        null,
                        $transactionId
                    );

                    $modalidadeVinculacaoDocumento = $this->modalidadeVinculacaoDocumentoRepository->findOneBy(
                        ['valor' => $this->parameterBag->get(
                            'constantes.entidades.modalidade_vinculacao_documento.const_1'
                        )]
                    );

                    $vinculacaoDocumentoDTO = new VinculacaoDocumento();
                    $vinculacaoDocumentoDTO->setDocumento($documento);
                    $vinculacaoDocumentoDTO->setDocumentoVinculado($documentoClonado);
                    $vinculacaoDocumentoDTO->setModalidadeVinculacaoDocumento($modalidadeVinculacaoDocumento);

                    $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);
                }
            }

            if ($restDto->getProcessoOrigem() &&
                !$restDto->getEditavel() &&
                !$restDto->getTarefaOrigem() &&
                !$restDto->getDocumentoOrigem() &&
                !$restDto->getDocumentoAvulsoOrigem() &&
                true === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
                $juntadaDTO = new Juntada();
                $juntadaDTO->setDocumento($documento);
                $juntadaDTO->setDescricao('JUNTADA DE DOCUMENTO');
                $this->juntadaResource->create($juntadaDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
