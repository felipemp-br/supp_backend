<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadUsuarioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

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
        $entity->setUsername('00000000001');
        $entity->setNome('JOÃO USER');
        $entity->setAssinaturaHTML('João User');
        $entity->setEmail('joao.user@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000002');
        $entity->setNome('JOÃO COLABORADOR');
        $entity->setAssinaturaHTML('João Colaborador');
        $entity->setEmail('joao.colaborador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000003');
        $entity->setNome('JOÃO ADMIN');
        $entity->setAssinaturaHTML('João Admin');
        $entity->setEmail('joao.admin@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000004');
        $entity->setNome('JOÃO ROOT');
        $entity->setAssinaturaHTML('João ROOT');
        $entity->setEmail('joao.root@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000005');
        $entity->setNome('PEDRO USER');
        $entity->setAssinaturaHTML('Pedro User');
        $entity->setEmail('pedro.user@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000006');
        $entity->setNome('PEDRO COLABORADOR');
        $entity->setAssinaturaHTML('Pedro Colaborador');
        $entity->setEmail('pedro.colaborador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000007');
        $entity->setNome('PEDRO ADMIN');
        $entity->setAssinaturaHTML('Pedro Admin');
        $entity->setEmail('pedro.admin@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000008');
        $entity->setNome('PEDRO ROOT');
        $entity->setAssinaturaHTML('Pedro ROOT');
        $entity->setEmail('pedro.root@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000009');
        $entity->setNome('PAULO USER');
        $entity->setAssinaturaHTML('Paulo User');
        $entity->setEmail('paulo.user@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000010');
        $entity->setNome('PAULO COLABORADOR');
        $entity->setAssinaturaHTML('Paulo Colaborador');
        $entity->setEmail('paulo.colaborador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000011');
        $entity->setNome('PAULO ADMIN');
        $entity->setAssinaturaHTML('Paulo Admin');
        $entity->setEmail('paulo.admin@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('00000000012');
        $entity->setNome('PAULO ROOT');
        $entity->setAssinaturaHTML('Paulo ROOT');
        $entity->setEmail('paulo.root@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
