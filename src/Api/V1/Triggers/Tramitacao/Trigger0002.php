<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tramitacao/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tramitacao;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Recebe a remessa setando dataHoraRecebimento e setorAtual do processo
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ProcessoResource $processoResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ProcessoResource $processoResource
    ) {
        $this->processoResource = $processoResource;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Tramitacao|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$entity->getUsuarioRecebimento() &&
            $restDto->getUsuarioRecebimento() &&
            $entity->getPessoaDestino() &&
            $restDto->getSetorAtual()
            ) {
            $restDto->setDataHoraRecebimento(new DateTime());

            $processoDTO = $this->processoResource->getDtoForEntity(
                $restDto->getProcesso()->getId(),
                Processo::class
            );
            $processoDTO->setSetorAtual($restDto->getSetorAtual());
            $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId, true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
