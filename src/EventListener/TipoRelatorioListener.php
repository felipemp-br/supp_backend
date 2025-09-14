<?php

declare(strict_types=1);
/**
 * /src/EventListener/TipoRelatorioListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * Class TipoRelatorioListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoRelatorioListener
{
    /**
     * @var AclProviderInterface AclProviderInterface
     */
    private AclProviderInterface $aclProvider;

    protected array $agenda = [];

    /**
     * TipoRelatorioListener constructor.
     *
     * @param AclProviderInterface $aclProvider
     */
    public function __construct(AclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

            if ($entity instanceof TipoRelatorio) {
            $this->agenda[] = $entity;
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event): void
    {
        if (!empty($this->agenda)) {
            foreach ($this->agenda as $entity) {
                $objectIdentity = ObjectIdentity::fromDomainObject($entity);
                $acl = $this->aclProvider->createAcl($objectIdentity);
                $securityIdentity = $entity->getCriadoPor() ?
                    new RoleSecurityIdentity('ROLE_ADMIN') :
                    new RoleSecurityIdentity('ROLE_USER');
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $this->aclProvider->updateAcl($acl);
            }

            $this->agenda = [];
        }
    }
}
