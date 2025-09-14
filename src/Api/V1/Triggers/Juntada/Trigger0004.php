<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AreaTrabalhoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Apaga todas as áreas de trabalho do documento juntado!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private AreaTrabalhoResource $areaTrabalhoResource;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        AreaTrabalhoResource $areaTrabalhoResource
    ) {
        $this->areaTrabalhoResource = $areaTrabalhoResource;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        //foreach ($restDto->getDocumento()->getAreasTrabalhos() as $areaTrabalho) {
        //    $this->areaTrabalhoResource->delete($areaTrabalho->getId(), $transactionId);
        //}
    }

    public function getOrder(): int
    {
        return 1;
    }
}
