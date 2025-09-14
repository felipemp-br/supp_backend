<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Bookmark/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Bookmark;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Bookmark as BookmarkDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger= Cria a Bookmark!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            BookmarkDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($this->tokenStorage->getToken()?->getUser()?->getId()) {
            $restDto->setUsuario($this->tokenStorage->getToken()->getUser());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
