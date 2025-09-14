<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Gera um lock de edição do conteúdo por 5 minutos!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // está editando o conteúdo?
        if ($restDto->getConteudo() &&
            ($restDto->getHash() !== $entity->getHash())) {
            $restDto->setDataHoraLockEdicao(new DateTime());
            $restDto->setUsernameLockEdicao($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
            $restDto->setInteracoes($entity->getInteracoes() + 1);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
