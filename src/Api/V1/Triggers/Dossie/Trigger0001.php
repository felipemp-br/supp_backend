<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Dossie;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Quando não informada a data de consulta, esta será a data corrente.
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            DossieDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DossieDTO $restDto
     * @param EntityInterface|DossieEntity $entity
     * @param string $transactionId
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(RestDtoInterface | DossieDTO $restDto, EntityInterface | DossieEntity $entity, string $transactionId): void
    {

        if (is_null($restDto->getDataConsulta())) {
            $restDto->setDataConsulta(new DateTime());
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}
