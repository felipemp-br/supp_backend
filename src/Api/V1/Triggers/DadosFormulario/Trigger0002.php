<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DadosFormulario/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DadosFormulario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Verifica se o JSON é inválido.
 *
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            DadosFormularioDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
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
        if (null === $restDto->getInvalido()) {
            $restDto->setInvalido(empty($restDto->getDataValue()) || !json_validate($restDto->getDataValue()));
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
