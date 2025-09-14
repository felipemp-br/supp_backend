<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0022.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * Class Trigger0024.
 *
 * @descSwagger=Restringe a visibilidade de um processo a a depender da classificação.
 * @classeSwagger=Trigger0024
 */
class Trigger0024 implements TriggerInterface
{
    /**
     * Trigger0022 constructor.
     *
     * @param AclProviderInterface $aclProvider
     */
    public function __construct(private AclProviderInterface $aclProvider) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(
        RestDtoInterface | Processo | null $restDto,
        EntityInterface | ProcessoEntity $entity,
        string $transactionId
    ): void {
        // mudando a classificação para uma restrita?
        if ($restDto->getClassificacao() &&
            ($restDto->getClassificacao()->getId() !== $entity->getClassificacao()->getId())) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($restDto->getClassificacao()));
                $pemissionMap = new BasicPermissionMap();
                if (!$acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                )) {
                    $restDto->setAcessoRestrito(true);
                }
            } catch (Throwable $e) {
                $restDto->setAcessoRestrito(true);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
