<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/TipoRelatorio/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\TipoRelatorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoRelatorio as TipoRelatorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    protected AuthorizationCheckerInterface $authorizationChecker;
    protected TokenStorageInterface $tokenStorage;

    /**
     * Pipe0001 constructor.
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
            TipoRelatorioDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            (false === $this->authorizationChecker->isGranted('ROLE_ADMIN'))) {
            $restDto->setDQL(null);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
