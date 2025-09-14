<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadColaboradorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Colaborador;

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
        $colaborador->setCargo($this->getReference('Cargo-SERVIDOR'));
        $colaborador->setModalidadeColaborador($this->getReference('ModalidadeColaborador-SERVIDOR'));
        $colaborador->setUsuario($this->getReference('Usuario-00000000000'));
        $manager->persist($colaborador);

        $this->addReference('Colaborador-00000000000', $colaborador);

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
        return ['prodexec'];
    }
}
