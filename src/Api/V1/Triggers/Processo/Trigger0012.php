<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Seta a data de encerramento do processo!
 * @classeSwagger=Trigger0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0012 implements TriggerInterface
{
    private TransicaoRepository $transicaoRepository;

    /**
     * Trigger0012 constructor.
     */
    public function __construct(
        TransicaoRepository $transicaoRepository
    ) {
        $this->transicaoRepository = $transicaoRepository;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$entity->getDataHoraEncerramento() &&
            ($restDto->getModalidadeFase()->getId() !== $entity->getModalidadeFase()->getId()) &&
            ('CORRENTE' !== $restDto->getModalidadeFase()->getValor())) {
            $restDto->setDataHoraEncerramento(new DateTime());
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
