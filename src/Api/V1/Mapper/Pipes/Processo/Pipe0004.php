<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * Class Pipe0004.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0004 implements PipeInterface
{
    private AclProviderInterface $aclProvider;

    /**
     * AclListener constructor.
     */
    public function __construct(AclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getId()) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($entity));
                $pemissionMap = new BasicPermissionMap();
                $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );
                $restDto->setAcessoRestrito(false);
            } catch (Throwable $e) {
                $restDto->setAcessoRestrito(true);
            }
        }
        if (!$restDto->getAcessoRestrito() &&
            $entity->getClassificacao() &&
            $entity->getClassificacao()->getId()) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($entity->getClassificacao()));
                $pemissionMap = new BasicPermissionMap();
                $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );
                $restDto->setAcessoRestrito(false);
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
