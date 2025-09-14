<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadCargoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

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
        $cargo->setNome('SERVIDOR');
        $cargo->setDescricao('SERVIDOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('CONTADOR');
        $cargo->setDescricao('CONTADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('ADMINISTRADOR');
        $cargo->setDescricao('ADMINISTRADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('ESTAGIÁRIO');
        $cargo->setDescricao('ESTAGIÁRIO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('TERCEIRIZADO');
        $cargo->setDescricao('TERCEIRIZADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('BIBLIOTECÁRIO');
        $cargo->setDescricao('BIBLIOTECÁRIO');

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
        return ['prod', 'dev', 'test'];
    }
}
