<?php

declare(strict_types=1);
/**
 * /src/DevDataFixtures/ORM/Dev/LoadApiKeyData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadApiKeyData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadApiKeyData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $entity = new ApiKey();
        $entity->generateToken();
        $entity->setNome('APIKEY 1');
        $entity->setDescricao('APIKEY 1');
        $entity->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($entity);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
