<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadColaboradorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

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
        $colaborador->setUsuario($this->getReference('Usuario-00000000005', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000005', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-10000000002', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-10000000002', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-10000000003', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-10000000003', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-10000000004', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-10000000004', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-10000000005', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-10000000005', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-20000000002', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-20000000002', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-20000000003', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-20000000003', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-20000000004', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-20000000004', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-20000000005', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-20000000005', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-30000000002', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-30000000002', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-30000000003', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-30000000003', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-30000000004', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-30000000004', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-ADVOGADO DA UNIÃO', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-MEMBRO', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-30000000005', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-30000000005', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-SERVIDOR', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-SERVIDOR', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-40000000002', Usuario::class));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-40000000002', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-SERVIDOR', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-SERVIDOR', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-40000000003', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-40000000003', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-SERVIDOR', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-SERVIDOR', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-40000000004', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-40000000004', $colaborador);

        $colaborador = new Colaborador();
        $colaborador->setCargo($this->getReference('Cargo-SERVIDOR', Cargo::class));
        $colaborador->setModalidadeColaborador(
            $this->getReference('ModalidadeColaborador-SERVIDOR', ModalidadeColaborador::class)
        );
        $colaborador->setUsuario($this->getReference('Usuario-40000000005', Usuario::class));

        $manager->persist($colaborador);

        $this->addReference('Colaborador-40000000005', $colaborador);

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
        return ['dev'];
    }
}
