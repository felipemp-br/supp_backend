<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Seta usuário como inativo, caso não seja usuário criado pelo admin ou coordenador de unidade
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Usuario::class => [
                'beforeCreate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($this->tokenStorage->getToken() &&
            (false === $this->authorizationChecker->isGranted('ROLE_ADMIN')) &&
            (false === $this->authorizationChecker->isGranted('ROLE_COORDENADOR_UNIDADE'))) {
            $restDto->setEnabled(false);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
