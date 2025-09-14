<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModuloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Modulo;

/**
 * Class LoadModuloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModuloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $entity = new Modulo();
        $entity->setNome('ADMINISTRATIVO');
        $entity->setDescricao('MÓDULO ADMINISTRATIVO');
        $entity->setSigla('AD');
        $entity->setPrefixo('supp_core.administrativo_backend');
        $entity->setAtivo(true);

        // Persist entity
        $manager->persist($entity);

        $this->addReference('Modulo-'.$entity->getNome(), $entity);

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
        return ['prodexec'];
    }
}
