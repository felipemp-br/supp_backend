<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0011.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0011.
 *
 * @descSwagger=Após alterar tipo do documento atualiza label da etiqueta!
 * @classeSwagger=Trigger0011
 */
class Trigger0011 implements TriggerInterface
{
    public function __construct(
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
    ) {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterUpdate'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getJuntadaAtual() && $restDto->getTarefaOrigem() && !$restDto->getDocumentoAvulsoRemessa()) {
            foreach ($restDto->getTarefaOrigem()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($restDto->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                    DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    /** @var VinculacaoEtiqueta $vinculacaoEtiquetaDTO */
                    $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource
                        ->getDtoForEntity($vinculacaoEtiqueta->getId(), VinculacaoEtiqueta::class);
                    $vinculacaoEtiquetaDTO->setLabel($restDto->getTipoDocumento()->getSigla());
                    $this->vinculacaoEtiquetaResource->update(
                        $vinculacaoEtiqueta->getId(),
                        $vinculacaoEtiquetaDTO,
                        $transactionId
                    );
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
