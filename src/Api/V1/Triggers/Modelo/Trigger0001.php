<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculoacaoModeloDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoModeloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger= Cria a VinculacaoModelo!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(private VinculacaoModeloResource $vinculacaoModeloResource,
                                private AuthorizationCheckerInterface $authorizationChecker) {
    }

    public function supports(): array
    {
        return [
            Modelo::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Modelo|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getSetor()
            && !$restDto->getUsuario()
            && !$restDto->getUnidade()
            && !$restDto->getModalidadeOrgaoCentral()
            && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return;
        }

        /** @var Modelo $restDto */
        $vinculacaoModeloDTO = new VinculoacaoModeloDTO();
        $vinculacaoModeloDTO->setModelo($entity);
        $vinculacaoModeloDTO->setSetor($restDto->getSetor());
        $vinculacaoModeloDTO->setUsuario($restDto->getUsuario());
        $vinculacaoModeloDTO->setUnidade($restDto->getUnidade());
        $vinculacaoModeloDTO->setModalidadeOrgaoCentral($restDto->getModalidadeOrgaoCentral());
        $this->vinculacaoModeloResource->create($vinculacaoModeloDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
