<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadUsuarioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

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
        $entity->setValidado(true);

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
        $entity->setNome('JOÃO ARQUIVISTA');
        $entity->setAssinaturaHTML('João Arquivista');
        $entity->setEmail('joao.arquivista@teste.com');
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
        $entity->setUsername('00000000005');
        $entity->setNome('JOÃO COORDENADOR');
        $entity->setAssinaturaHTML('João Coordenador');
        $entity->setEmail('joao.coordenador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('10000000001');
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
        $entity->setUsername('10000000002');
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
        $entity->setUsername('10000000003');
        $entity->setNome('PEDRO ARQUIVISTA');
        $entity->setAssinaturaHTML('Pedro Arquivista');
        $entity->setEmail('pedro.arquivista@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('10000000004');
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
        $entity->setUsername('10000000005');
        $entity->setNome('PEDRO COORDENADOR');
        $entity->setAssinaturaHTML('Pedro Coordenador');
        $entity->setEmail('pedro.coordenador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('20000000001');
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
        $entity->setUsername('20000000002');
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
        $entity->setUsername('20000000003');
        $entity->setNome('PAULO ARQUIVISTA');
        $entity->setAssinaturaHTML('Paulo Arquivista');
        $entity->setEmail('paulo.arquivista@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('20000000004');
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
        $entity->setUsername('20000000005');
        $entity->setNome('PAULO COORDENADOR');
        $entity->setAssinaturaHTML('Paulo Coordenador');
        $entity->setEmail('paulo.coordenador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('30000000001');
        $entity->setNome('LUCAS USER');
        $entity->setAssinaturaHTML('Lucas User');
        $entity->setEmail('lucas.user@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('30000000002');
        $entity->setNome('LUCAS COLABORADOR');
        $entity->setAssinaturaHTML('Lucas Colaborador');
        $entity->setEmail('lucas.colaborador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('30000000003');
        $entity->setNome('LUCAS ARQUIVISTA');
        $entity->setAssinaturaHTML('Lucas Arquivista');
        $entity->setEmail('lucas.arquivista@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('30000000004');
        $entity->setNome('LUCAS ADMIN');
        $entity->setAssinaturaHTML('Lucas Admin');
        $entity->setEmail('lucas.admin@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('30000000005');
        $entity->setNome('LUCAS COORDENADOR');
        $entity->setAssinaturaHTML('Lucas Coordenador');
        $entity->setEmail('lucas.coordenador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('40000000001');
        $entity->setNome('MATEUS USER');
        $entity->setAssinaturaHTML('Mateus User');
        $entity->setEmail('mateus.user@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(0);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('40000000002');
        $entity->setNome('MATEUS COLABORADOR');
        $entity->setAssinaturaHTML('Mateus Colaborador');
        $entity->setEmail('mateus.colaborador@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('40000000003');
        $entity->setNome('MATEUS ARQUIVISTA');
        $entity->setAssinaturaHTML('Mateus Arquivista');
        $entity->setEmail('mateus.arquivista@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('40000000004');
        $entity->setNome('MATEUS ADMIN');
        $entity->setAssinaturaHTML('Mateus Admin');
        $entity->setEmail('mateus.admin@teste.com');
        $entity->setPlainPassword('Agu123456');
        $entity->setEncoder('sodium');
        $entity->setEnabled(true);
        $entity->setNivelAcesso(1);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Usuario-'.$entity->getUsername(), $entity);

        $entity = new Usuario();
        $entity->setUsername('40000000005');
        $entity->setNome('MATEUS COORDENADOR');
        $entity->setAssinaturaHTML('Mateus Coordenador');
        $entity->setEmail('mateus.coordenador@teste.com');
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
        return ['dev'];
    }
}
