<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Desentranhamento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Seta a juntada desentranhada como inativa e retira o status de juntado do documento!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private JuntadaResource $juntadaResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource
    ) {
        $this->juntadaResource = $juntadaResource;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'afterCreate',
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
        /** @var Juntada $juntadaDTO */
        $juntadaDTO = $this->juntadaResource->getDtoForEntity(
            $restDto->getJuntada()->getId(),
            Juntada::class
        );
        $juntadaDTO->setAtivo(false);
        $juntadaDTO->setJuntadaDesentranhada($restDto->getJuntada());

        $this->juntadaResource->update(
            $restDto->getJuntada()->getId(),
            $juntadaDTO,
            $transactionId,
            true
        );
    }

    public function getOrder(): int
    {
        return 4;
    }
}
