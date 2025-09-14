<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Usuario;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Security\RolesServiceInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0002.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0002 implements PipeInterface
{
    /**
     * Pipe0002 constructor.
     */
    public function __construct(
        private RolesServiceInterface $rolesService,
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param Usuario|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() instanceof Usuario &&
            $this->tokenStorage->getToken()->getUser()->getUserIdentifier() === $entity->getUserIdentifier()) {
            $roles = array_unique($this->tokenStorage->getToken()->getRoleNames());
            foreach ($roles as $role) {
                $restDto->addRole($role);
            }
            return;
        }
        if ($this->tokenStorage->getToken() && $this->authorizationChecker->isGranted('ROLE_ADMIN') &&
            $this->requestStack->getCurrentRequest() &&
            (null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->isAdmin)) {
                $roles = $this->rolesService->getContextualRoles($entity);
                foreach ($roles as $role) {
                    $restDto->addRole($role);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
