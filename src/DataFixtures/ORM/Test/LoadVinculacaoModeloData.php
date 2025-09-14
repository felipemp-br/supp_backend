<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;

/**
 * Class LoadVinculacaoModeloData.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadVinculacaoModeloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $entity1 = new VinculacaoModeloEntity();
        $entity1->setModelo($this->getReference('Modelo-TESTE_FIXTURE_1', Modelo::class));
        $entity1->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($entity1);

        $entity2 = new VinculacaoModeloEntity();
        $entity2->setModelo($this->getReference('Modelo-TESTE_FIXTURE_2', Modelo::class));
        $entity2->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($entity2);

        $entity3 = new VinculacaoModeloEntity();
        $entity3->setModelo($this->getReference('Modelo-TESTE_FIXTURE_3', Modelo::class));
        $entity3->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($entity3);

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
        return ['testModelo'];
    }
}
