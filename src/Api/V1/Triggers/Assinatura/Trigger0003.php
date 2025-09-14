<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Assinatura/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Seta manualmente o criadoPor, exceto no contexto de clone!
 *
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TransactionManager $transactionManager
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            AssinaturaDTO::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @return void
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$this->transactionManager->getContext('clonarAssinatura', $transactionId)
            && $this->tokenStorage->getToken()
            && $this->tokenStorage->getToken()->getUser()
        ) {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            $entity->setCriadoPor(
                $usuario
            );
        }

        if ($this->transactionManager->getContext('clonarAssinatura', $transactionId)) {
            $entity->setCriadoPor(
                $restDto->getCriadoPor()
            );
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
