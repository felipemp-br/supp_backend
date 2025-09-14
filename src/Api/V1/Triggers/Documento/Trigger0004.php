<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Caso seja o documento um documento origem, o documento será clonado!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    private JuntadaResource $juntadaResource;

    private VolumeResource $volumeResource;

    private AuthorizationCheckerInterface $authorizationChecker;

    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource,
        DocumentoResource $documentoResource,
        VolumeResource $volumeResource,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        TransactionManager $transactionManager
    ) {
        $this->documentoResource = $documentoResource;
        $this->juntadaResource = $juntadaResource;
        $this->volumeResource = $volumeResource;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Documento|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $documentoOrigem = $entity->getDocumentoOrigem();
        $processoOrigem = $entity->getProcessoOrigem();
        if ($documentoOrigem) {
            $this->transactionManager->addContext(
                new Context('copia_documento', $transactionId),
                $transactionId
            );

            /** @var DocumentoEntity $entity */
            $entity = $this->documentoResource->clonar($documentoOrigem->getId(), $entity, $transactionId);
            $entity->setDocumentoOrigem($documentoOrigem);
            $entity->setProcessoOrigem($processoOrigem);

            // juntada imediata
            $juntadaDTO = new Juntada();
            $juntadaDTO->setDocumento($entity);
            $juntadaDTO->setDescricao(
                'CÓPIA ORIUNDA DO NUP '.$documentoOrigem->getJuntadaAtual()->getVolume()->
                getProcesso()->getNUPFormatado().' SEQ. '.$documentoOrigem->getJuntadaAtual()->
                getNumeracaoSequencial()
            );

            $this->juntadaResource->create($juntadaDTO, $transactionId);
            $this->transactionManager->removeContext('copia_documento', $transactionId);
        } elseif ($this->tokenStorage->getToken()) {
            if (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
                if ($processoOrigem) {
                    // verifica contexto para saber se o documento foi produzido internamente,
                    // mesmo para usuário externo, evitando criação de juntada
                    $context = $this->transactionManager->getContext(
                        'origem_documento',
                        $this->transactionManager->getCurrentTransactionId()
                    );
                    //Caso seja usuário externo, cria a juntada do documento criado ao processo
                    //Retirei o getProtocoloEletronico para permitir que usuário faça requerimento em NUP existente
                    if ($entity->getProcessoOrigem()->getId() &&
                        !$entity->getJuntadaAtual() &&
                        !$this->transactionManager->getContext('documento_resposta_oficio', $transactionId) &&
                        (is_null($context) || $context->getValue() !== 'interno')
                    ) {
                        $juntadaDTO = new Juntada();
                        $juntadaDTO->setDocumento($entity);
                        $juntadaDTO->setVolume(
                            $this->volumeResource->getRepository()->findVolumeAbertoByProcessoId(
                                $entity->getProcessoOrigem()->getId()
                            )
                        );
                        $juntadaDTO->setDescricao('DOCUMENTO RECEBIDO VIA PROTOCOLO EXTERNO');

                        $this->juntadaResource->create($juntadaDTO, $transactionId);
                    }
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
