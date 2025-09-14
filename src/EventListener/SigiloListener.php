<?php

declare(strict_types=1);
/**
 * /src/EventListener/SigiloListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SuppCore\AdministrativoBackend\Entity\Sigilo;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * Class SigiloListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SigiloListener
{
    /**
     * @var AclProviderInterface AclProviderInterface
     */
    private AclProviderInterface $aclProvider;

    protected array $agenda = [];

    /**
     * SigiloListener constructor.
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

        if ($entity instanceof Sigilo) {
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
                if (!$entity->getCriadoPor()) {
                    continue;
                }
                $target = $entity->getProcesso();

                if (!$target) {
                    $target = $entity->getDocumento();
                }

                // revoga tudo
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($target));

                if (null !== $acl) {
                    $aces = $acl->getObjectAces();

                    $size = count($aces) - 1;
                    reset($aces);

                    for ($i = $size; $i >= 0; --$i) {
                        $acl->deleteObjectAce($i);
                    }

                    $this->aclProvider->updateAcl($acl);
                }

                // creating the ACL
                $securityIdentity = UserSecurityIdentity::fromAccount($entity->getCriadoPor());

                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $this->aclProvider->updateAcl($acl);
            }

            $this->agenda = [];
        }
    }
}
