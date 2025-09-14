<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepositorio;
use SuppCore\AdministrativoBackend\Entity\Repositorio;

/**
 * Class LoadRepositorioData.
 *
 * @author Lucas Campelo <lucas.campelo@agu.gov.br>
 */
class LoadRepositorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $repositorio1 = new Repositorio();
        $repositorio1->setDocumento($this->getReference('Documento-MODELO DESPACHO DE APROVAÇÃO', Documento::class));
        $repositorio1->setModalidadeRepositorio(
            $this->getReference('ModalidadeRepositorio-JURISPRUDÊNCIA', ModalidadeRepositorio::class)
        );
        $repositorio1->setNome('Repositório de Teste 1');
        $repositorio1->setDescricao('Este repositório é utilizado em testes automatizados.');
        $repositorio1->setAtivo(true);

        // Persist entity
        $manager->persist($repositorio1);

        $this->addReference('Repositorio-'.$repositorio1->getNome(), $repositorio1);

        $repositorio2 = new Repositorio();
        $repositorio2->setDocumento($this->getReference('Documento-TEMPLATE OFÍCIO', Documento::class));
        $repositorio2->setModalidadeRepositorio(
            $this->getReference('ModalidadeRepositorio-JURISPRUDÊNCIA', ModalidadeRepositorio::class)
        );
        $repositorio2->setNome('Repositório de Teste 2');
        $repositorio2->setDescricao('Este repositório é utilizado em testes automatizados.');
        $repositorio2->setAtivo(true);

        // Persist entity
        $manager->persist($repositorio2);

        $this->addReference('Repositorio-'.$repositorio2->getNome(), $repositorio2);

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
        return 5;
    }
}
