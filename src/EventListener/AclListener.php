<?php

declare(strict_types=1);
/**
 * /src/EventListener/AclListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * Class AclListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AclListener
{
    private AclProviderInterface $aclProvider;

    protected array $agenda = [];

    private array $entidadesAgendamentoPrePersist = [
        Processo::class,
        Documento::class,
        Classificacao::class,
    ];

    /**
     * AclListener constructor.
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

        if (in_array(get_class($entity), $this->entidadesAgendamentoPrePersist)) {
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
                $securityIdentity = new RoleSecurityIdentity('ROLE_USER');

                switch (get_class($entity)) {
                    case Processo::class:
                        if ($entity->getCriadoPor() && $entity->getAcessoRestrito()) {
                            $securityIdentity = UserSecurityIdentity::fromAccount($entity->getCriadoPor());
                        } elseif ($entity->getAcessoRestrito() && $entity->getOrigemDados()
                            && 'BARRAMENTO_PEN' === $entity->getOrigemDados()->getFonteDados()) {
                            $securityIdentity = new RoleSecurityIdentity(
                                'ACL_UNIDADE_'.$entity->getSetorAtual()->getUnidade()->getId()
                            );
                        } elseif ($entity->getAcessoRestrito()) {
                            $securityIdentity =
                                new RoleSecurityIdentity('ACL_SETOR_'.$entity->getSetorAtual()->getId());
                        }
                        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                        $this->aclProvider->updateAcl($acl);
                        break;
                    case Classificacao::class:
                        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_MASTER);
                        $this->aclProvider->updateAcl($acl);
                        break;
                    case Documento::class:
                        if ($entity->getAcessoRestrito()) {
                            if ($entity->getCriadoPor()) {
                                $securityIdentity = UserSecurityIdentity::fromAccount($entity->getCriadoPor());
                            } elseif ($entity->getOrigemDados()
                                && 'BARRAMENTO_PEN' === $entity->getOrigemDados()->getFonteDados()) {
                                $securityIdentity = new RoleSecurityIdentity(
                                    'ACL_UNIDADE_'.
                                    $entity->getProcessoOrigem()->getSetorAtual()->getUnidade()->getId()
                                );
                            } else {
                                $securityIdentity = new RoleSecurityIdentity(
                                    'ACL_SETOR_'.$entity->getProcessoOrigem()->getSetorAtual()->getId()
                                );
                            }
                        }
                        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                        $this->aclProvider->updateAcl($acl);
                        break;
                }
            }

            $this->agenda = [];
        }
    }
}
