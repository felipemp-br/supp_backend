<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Cronjob/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Cronjob;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CronjobResource;
use SuppCore\AdministrativoBackend\Entity\Cronjob;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as CronjobDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Atualiza informações de ultima execução do job.
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     */
    public function __construct(private CronjobResource $cronJobResource) {
    }

    public function supports(): array
    {
        return [
            CronjobDTO::class => [
                'afterExecuteJobCommand'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     */
    public function execute(
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->cronJobResource->push(
            $entity,
            '/v1/administrativo/cron_job/'.$entity->getId(),
            $transactionId,
            ['usuarioUltimaExecucao']
        );
    }

    public function getOrder(): int
    {
        return 1;
    }
}
