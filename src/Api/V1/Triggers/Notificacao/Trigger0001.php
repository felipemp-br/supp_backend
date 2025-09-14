<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Notificacao/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Notificacao;

use DateInterval;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Caso não informado, insere a data de expiração da notificação em 30 dias no futuro!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Notificacao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getDataHoraExpiracao()) {
            $tempo = new DateTime();
            $tempo->add(new DateInterval('P30D'));

            $restDto->setDataHoraExpiracao($tempo);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
