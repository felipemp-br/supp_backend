<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadCargoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Cargo;

/**
 * Class LoadCargoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCargoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR FEDERAL');
        $cargo->setDescricao('PROCURADOR FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('ADVOGADO DA UNIÃO');
        $cargo->setDescricao('ADVOGADO DA UNIÃO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DA FAZENDA NACIONAL');
        $cargo->setDescricao('PROCURADOR DA FAZENDA NACIONAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DO BANCO CENTRAL');
        $cargo->setDescricao('PROCURADOR DO BANCO CENTRAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
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
        return ['dev'];
    }
}
