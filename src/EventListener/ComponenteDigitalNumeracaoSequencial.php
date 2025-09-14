<?php

declare(strict_types=1);
/**
 * /src/EventListener/ComponenteDigitalListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;

/**
 * Class ComponenteDigitalNumeracaoSequencial.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ComponenteDigitalNumeracaoSequencial
{
    protected ComponenteDigitalResource $componenteDigitalResource;
    protected array $agenda = [];
    protected array $documentosProcessadosId = [];
    protected bool $updateProibido = false;

    /**
     * ComponenteDigitalNumeracaoSequencial constructor.
     *
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(ComponenteDigitalResource $componenteDigitalResource)
    {
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ComponenteDigital) {
            if ($entity->getApagadoEm()) {
                return;
            }

            if ($entity->getDocumento()) {
                $numeracaoSequencial = $this->componenteDigitalResource->getRepository()
                        ->findMaxNumeracaoSequencialByDocumentoId($entity->getDocumento()->getId());
                $entity->setNumeracaoSequencial($numeracaoSequencial + 1);
            } else {
                $entity->setNumeracaoSequencial(1);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ComponenteDigital) {
            if ($this->updateProibido) {
                return;
            }

            $documento = $entity->getDocumento();

            if (in_array($entity->getDocumento()->getId(), $this->documentosProcessadosId)) {
                return;
            }

            $this->documentosProcessadosId[] = $documento->getId();

            $sequencialSolicitado = $entity->getNumeracaoSequencial();

            $numeracaoSequencialMaxima = count($documento->getComponentesDigitais());

            // não deixa ser maior que o máximo lógico
            if ($sequencialSolicitado > $numeracaoSequencialMaxima) {
                $sequencialSolicitado = $numeracaoSequencialMaxima;
                $entity->setNumeracaoSequencial($sequencialSolicitado);
            }

            $i = 1;

            // o documento sempre retorna os componentes na ordem crescente de numeracao
            // @ORM\OrderBy({"numeracaoSequencial" = "ASC"})
            foreach ($documento->getComponentesDigitais() as $componenteDigital) {
                if ($componenteDigital->getId() == $entity->getId()) {
                    continue;
                }

                if ($i >= $sequencialSolicitado) {
                    $componenteDigital->setNumeracaoSequencial($i + 1);
                } else {
                    $componenteDigital->setNumeracaoSequencial($i);
                }

                // reordenação
                $this->agenda[] = $componenteDigital;
                ++$i;
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postSoftDelete(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ComponenteDigital) {
            $this->agenda = [];

            $i = 1;

            $this->updateProibido = true;

            // o documento sempre retorna os componentes na ordem crescente de numeracao
            // @ORM\OrderBy({"numeracaoSequencial" = "ASC"})
            foreach ($entity->getDocumento()->getComponentesDigitais() as $componenteDigital) {
                if (($key = array_search($componenteDigital, $this->agenda)) !== false) {
                    array_splice($this->agenda, $key, 1);
                }

                if ($entity->getId() == $componenteDigital->getId()) {
                    continue;
                }

                if ($componenteDigital->getApagadoEm()) {
                    continue;
                }

                $componenteDigital->setNumeracaoSequencial($i);

                // reordenação
                $this->agenda[] = $componenteDigital;
                ++$i;
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function postFlush(PostFlushEventArgs $event)
    {
        if (!empty($this->agenda)) {
            $em = $event->getObjectManager();

            foreach ($this->agenda as $entity) {
                $em->persist($entity);
            }

            $this->agenda = [];

            $em->flush();
        }
    }
}
