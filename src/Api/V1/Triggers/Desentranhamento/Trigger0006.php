<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Desentranhamento/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Retira as vinculações de documentos para juntadas desentranhadas!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    /**
     * @param VinculacaoDocumentoResource $vinculacaoDocumentoResource
     */
    public function __construct(
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource
    ) {
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        foreach ($restDto->getJuntada()->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
            $this->vinculacaoDocumentoResource->delete($vinculacaoDocumento->getId(), $transactionId);
        };
    }

    public function getOrder(): int
    {
        return 2;
    }
}
