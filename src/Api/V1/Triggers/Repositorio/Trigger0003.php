<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Repositorio/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRepositorioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Cria a vinculação entre o repositório e o usuário!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private VinculacaoRepositorioResource $vinculacaoRepositorioResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        VinculacaoRepositorioResource $vinculacaoRepositorioResource,
    ) {
        $this->vinculacaoRepositorioResource = $vinculacaoRepositorioResource;
    }

    public function supports(): array
    {
        return [
            Repositorio::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Repositorio|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $vinculacaoRepositorioDTO = new VinculacaoRepositorio();
        $vinculacaoRepositorioDTO->setRepositorio($entity);
        $vinculacaoRepositorioDTO->setUsuario($restDto->getUsuario());
        $vinculacaoRepositorioDTO->setSetor($restDto->getSetor());
        $vinculacaoRepositorioDTO->setModalidadeOrgaoCentral($restDto->getModalidadeOrgaoCentral());
        $vinculacaoRepositorioDTO->setUnidade($restDto->getUnidade());
        $this->vinculacaoRepositorioResource->create($vinculacaoRepositorioDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
