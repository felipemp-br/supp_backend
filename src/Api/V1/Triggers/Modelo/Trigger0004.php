<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeModeloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger= Define a modalidadeModelo de acordo com o request
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{

    /**
     * Trigger0004 constructor.
     */
    public function __construct(private ModalidadeModeloResource $modalidadeModeloResource,
                                private ParameterBagInterface $parameterBag,
                                private AuthorizationCheckerInterface $authorizationChecker) {
    }

    public function supports(): array
    {
        return [
            Modelo::class => [
                'beforeCreate',
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
        if (!$restDto->getModalidadeModelo() && $restDto->getUsuario()) {
            $restDto->setModalidadeModelo(
                $this->modalidadeModeloResource->getRepository()
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_1')])
            );
        }

        if (!$restDto->getModalidadeModelo() && ($restDto->getSetor() || $restDto->getUnidade())) {
            $restDto->setModalidadeModelo(
                $this->modalidadeModeloResource->getRepository()
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_2')])
            );
        }

        if (!$restDto->getModalidadeModelo() && $restDto->getModalidadeOrgaoCentral()) {
            $restDto->setModalidadeModelo(
                $this->modalidadeModeloResource->getRepository()
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_3')])
            );
        }

        if (!$restDto->getModalidadeModelo() && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $restDto->setModalidadeModelo(
                $this->modalidadeModeloResource->getRepository()
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_4')])
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
