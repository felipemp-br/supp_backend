<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/NumeroUnicoDocumento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\NumeroUnicoDocumento;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Insere o ano corrente
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            NumeroUnicoDocumento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $dateTime = new DateTime();
        $restDto->setAno((int) $dateTime->format('Y'));
    }

    public function getOrder(): int
    {
        return 1;
    }
}
