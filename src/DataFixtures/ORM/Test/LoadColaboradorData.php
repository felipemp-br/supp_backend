<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadColaboradorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Cargo;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\ModalidadeColaborador;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadColaboradorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadColaboradorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000002', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000003', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000003', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000004', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000006', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000007', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000007', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000008', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000008', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000010', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000010', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000011', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000011', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-PROCURADOR FEDERAL', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-00000000012', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000012', $colaborador);

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
        return 3;
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
