<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadObjetoAvaliadoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado;

/**
 * Class LoadObjetoAvaliadoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadObjetoAvaliadoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $objetoAvaliado = new ObjetoAvaliado();
        $objetoAvaliado->setClasse('CLASSE TESTE');
        $objetoAvaliado->setObjetoId(1);
        $objetoAvaliado->setAvaliacaoResultante(50);
        $objetoAvaliado->setQuantidadeAvaliacoes(1);

        // Persist entity
        $manager->persist($objetoAvaliado);

        // Create reference for later usage
        $this->addReference('ObjetoAvaliado-'.$objetoAvaliado->getObjetoId(), $objetoAvaliado);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
