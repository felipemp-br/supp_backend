<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0016.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0016.
 *
 * @descSwagger=Ajusta o setor e o usuário responsáveis de acordo com a tarefa ou o processo origem!
 * @classeSwagger=Trigger0016
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0016 implements TriggerInterface
{

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setModelo($entity->getModelo());
    }

    public function getOrder(): int
    {
        return 2;
    }
}
