<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoRoleData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

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
        $entity->setRole('ROLE_USER');
        $entity->setUsuario($this->getReference('Usuario-00000000001', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000001', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_COLABORADOR');
        $entity->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000002', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-00000000003', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000003', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ROOT');
        $entity->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000004', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_USER');
        $entity->setUsuario($this->getReference('Usuario-00000000005', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000005', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_COLABORADOR');
        $entity->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000006', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-00000000007', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000007', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ROOT');
        $entity->setUsuario($this->getReference('Usuario-00000000008', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000008', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_USER');
        $entity->setUsuario($this->getReference('Usuario-00000000009', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000009', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_COLABORADOR');
        $entity->setUsuario($this->getReference('Usuario-00000000010', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000010', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ADMIN');
        $entity->setUsuario($this->getReference('Usuario-00000000011', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000011', $entity);

        // Create new entity
        $entity = new VinculacaoRole();
        $entity->setRole('ROLE_ROOT');
        $entity->setUsuario($this->getReference('Usuario-00000000012', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        $this->addReference('VinculacaoRole-Usuario-00000000012', $entity);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
