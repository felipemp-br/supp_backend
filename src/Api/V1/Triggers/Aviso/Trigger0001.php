<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Aviso/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Aviso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Aviso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoAviso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoAvisoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger= Cria a VinculacaoAviso!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param VinculacaoAvisoResource $vinculacaoAvisoResource
     */
    public function __construct(
        private VinculacaoAvisoResource $vinculacaoAvisoResource
    ) {
    }

    public function supports(): array
    {
        return [
            Aviso::class => [
                'afterCreate',
                'afterUpdate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var Aviso $restDto */
        $vinculacaoAvisoDTO = new VinculacaoAviso();
        $vinculacaoAvisoDTO->setAviso($entity);
        $vinculacaoAvisoDTO->setUnidade($restDto->getUnidade());
        $vinculacaoAvisoDTO->setSetor($restDto->getSetor());
        $vinculacaoAvisoDTO->setModalidadeOrgaoCentral($restDto->getModalidadeOrgaoCentral());
        $vinculacaoAvisoDTO->setUsuario($restDto->getUsuario());
        $id = $entity->getId();
        if ($id === null) {
            $this->vinculacaoAvisoResource->create($vinculacaoAvisoDTO, $transactionId);            
        } else {
            $this->vinculacaoAvisoResource->update($this->vinculacaoAvisoResource->getRepository()->findBy(['aviso' => $entity])[0]->getId(), $vinculacaoAvisoDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
