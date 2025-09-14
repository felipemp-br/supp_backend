<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoDocumentoAssinaturaExterna;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger  =Remove assinatura vinculada a solicitação.
 *
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     *
     * @param AssinaturaResource $assinaturaResource
     */
    public function __construct(
        private readonly UsuarioResource $usuarioResource,
        private readonly AssinaturaResource $assinaturaResource,
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumentoAssinaturaExternaEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|VinculacaoDocumentoAssinaturaExternaDTO|null $restDto
     * @param EntityInterface|VinculacaoDocumentoAssinaturaExternaEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {

        $usuario = $entity->getUsuario() ?? $this->usuarioResource->findOneBy(['username' => $entity->getNumeroDocumentoPrincipal()]);
        if($usuario) {
            foreach($entity->getDocumento()->getComponentesDigitais() as $componenteDigital) {
                $assinatura = $this->assinaturaResource->findOneBy([
                    'componenteDigital' => $componenteDigital->getId(),
                    'criadoPor' => $usuario->getId(),
                ]);
                if($assinatura) {
                    $this->assinaturaResource->delete($assinatura->getId(), $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
