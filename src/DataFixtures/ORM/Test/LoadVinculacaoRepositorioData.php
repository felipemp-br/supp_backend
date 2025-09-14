<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Repositorio;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio;

/**
 * Class LoadVinculacaoRepositorioData.
 *
 * @author Lucas Campelo <lucas.campelo@agu.gov.br>
 */
class LoadVinculacaoRepositorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $vinculacaoRepositorio1 = new VinculacaoRepositorio();
        $vinculacaoRepositorio1->setRepositorio(
            $this->getReference('Repositorio-Repositório de Teste 1', Repositorio::class)
        );
        $vinculacaoRepositorio1->setSetor($this->getReference('Setor-PROTOCOLO-AGU-SEDE', Setor::class));

        // Persist entity
        $manager->persist($vinculacaoRepositorio1);

        $this->addReference(
            'VinculacaoRepositorio-'.$vinculacaoRepositorio1->getRepositorio()->getNome().'-1',
            $vinculacaoRepositorio1
        );

        $vinculacaoRepositorio2 = new VinculacaoRepositorio();
        $vinculacaoRepositorio2->setRepositorio(
            $this->getReference('Repositorio-Repositório de Teste 2', Repositorio::class)
        );
        $vinculacaoRepositorio2->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($vinculacaoRepositorio2);

        // Flush database changes
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return ['test'];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 6;
    }
}
