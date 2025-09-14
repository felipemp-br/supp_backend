<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso;

/**
 * Class LoadVinculacaoProcessoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoProcessoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $entity1 = new VinculacaoProcesso();
        $entity1->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $entity1->setObservacao('Observação 1');
        $entity1->setProcessoVinculado($this->getReference('Processo-00100000005202066', Processo::class));
        $entity1->setModalidadeVinculacaoProcesso(
            $this->getReference('ModalidadeVinculacaoProcesso-DESAPENSAMENTO', ModalidadeVinculacaoProcesso::class)
        );

        $manager->persist($entity1);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 5;
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
