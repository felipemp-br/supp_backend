<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadUsuarioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadUsuarioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadUsuarioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $entity = new Usuario();
        $entity->setUsername('00000000000');
        $entity->setNome('ADMINISTRADOR PADRÃO');
        $entity->setAssinaturaHTML('Administrador Padrão');
        $entity->setEmail('admin.padrao@inexistente.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

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
        return 2;
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
