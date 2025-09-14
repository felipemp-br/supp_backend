<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * Class Pipe0007.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0007 implements PipeInterface
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
            DocumentoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DocumentoDTO|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $restDto->setAcessoRestrito(false);

        try {
            $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($entity));
            $pemissionMap = new BasicPermissionMap();
            $acl->isGranted(
                $pemissionMap->getMasks('MASTER', null),
                [new RoleSecurityIdentity('ROLE_USER')]
            );
        } catch (Throwable) {
            $restDto->setAcessoRestrito(true);
            return;
        }

        if ($entity->getJuntadaAtual()) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject(
                    $entity->getJuntadaAtual()->getVolume()->getProcesso()
                ));
                $pemissionMap = new BasicPermissionMap();
                $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );
                $restDto->setAcessoRestrito(false);
            } catch (Throwable) {
                $restDto->setAcessoRestrito(true);
                return;
            }
        }

        if ($entity->getJuntadaAtual()?->getVolume()->getProcesso()->getClassificacao()) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject(
                    $entity->getJuntadaAtual()->getVolume()->getProcesso()->getClassificacao()
                ));
                $pemissionMap = new BasicPermissionMap();
                $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );
                $restDto->setAcessoRestrito(false);
            } catch (Throwable) {
                $restDto->setAcessoRestrito(true);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
