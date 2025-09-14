<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Assinatura/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Security\LdapService;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Caso seja assinatura A1 ocorrerá a assinatura com certificado institucional!
 *
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        private readonly LdapService $ldapService,
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            AssinaturaDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     *
     * @return void
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // MUDANÇA NA FORMA DE ASSINAR, NÃO PASSANDO MAIS PELA TRIGGER
    }

    /**
     * Verifica as credênciais do usuário.
     *
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkLdapCredentials(mixed $credentials, UserInterface $user): bool
    {
        if ($this->ldapService::TYPE_AUTH_AD) {
            return true;
        }

        return $credentials['password'] === $user->getPassword();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}
