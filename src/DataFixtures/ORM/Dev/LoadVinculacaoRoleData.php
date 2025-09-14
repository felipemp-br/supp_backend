<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadVinculacaoRoleData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;

/**
 * Class LoadVinculacaoRoleData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoRoleData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-Admin-00000000004', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-10000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-Admin-10000000004', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-20000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-Admin-20000000004', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-30000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-Admin-30000000004', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-40000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-Admin-40000000004', $entity);

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
        return 4;
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
