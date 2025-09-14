<?php

declare(strict_types=1);

/**
 * /src/DataFixtures/ORM/Dev/LoadMomentoDisparoRegraEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use SuppCore\AdministrativoBackend\Entity\MomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;

/**
 * Class LoadMomentoDisparoRegraEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadMomentoDisparoRegraEtiqueta extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('CRIAÇÃO DE PROCESSO ADMINISTRATIVO')
            ->setDescricao('MOMENTO EM QUE UM PROCESSO ADMINISTRATIVO É CRIADO.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_CRIACAO_PROCESSO_ADMINISTRATIVO->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'))
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addReference(
            'MomentoDisparoRegraEtiqueta-'
            .$momentoDisparoRegraEtiqueta->getSigla(),
            $momentoDisparoRegraEtiqueta
        );

        // Persist entity
        $manager->persist($momentoDisparoRegraEtiqueta);

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('DEFINIÇÃO/ALTERAÇÃO DO SETOR DO PROCESSO')
            ->setDescricao('MOMENTO EM QUE É DEFINIDO OU ALTERADO O SETOR ATUAL DO PROCESSO.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'))
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addReference(
            'MomentoDisparoRegraEtiqueta-'
            .$momentoDisparoRegraEtiqueta->getSigla(),
            $momentoDisparoRegraEtiqueta
        );

        // Persist entity
        $manager->persist($momentoDisparoRegraEtiqueta);

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('APÓS CRIAÇÃO DA PRIMEIRA TAREFA DO PROCESSO')
            ->setDescricao('MOMENTO EM QUE A PRIMEIRA TAREFA DO PROCESSO É CRIADA.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::PROCESSO_PRIMEIRA_TAREFA->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'))
            ->setDisponivelUsuario(false)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addReference(
            'MomentoDisparoRegraEtiqueta-'
            .$momentoDisparoRegraEtiqueta->getSigla(),
            $momentoDisparoRegraEtiqueta
        );

        // Persist entity
        $manager->persist($momentoDisparoRegraEtiqueta);

        $momentoDisparoRegraEtiqueta = (new MomentoDisparoRegraEtiqueta())
            ->setNome('(RE)DISTRIBUIÇÃO DA TAREFA')
            ->setDescricao('MOMENTO EM QUE É FEITA A DISTRIBUIÇÃO OU REDISTRIBUIÇÃO DA TAREFA.')
            ->setSigla(SiglaMomentoDisparoRegraEtiqueta::TAREFA_DISTRIBUICAO->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'))
            ->setDisponivelUsuario(true)
            ->setDisponivelSetor(true)
            ->setDisponivelUnidade(true)
            ->setDisponivelOrgaoCentral(true);

        $this->addReference(
            'MomentoDisparoRegraEtiqueta-'
            .$momentoDisparoRegraEtiqueta->getSigla(),
            $momentoDisparoRegraEtiqueta
        );

        // Persist entity
        $manager->persist($momentoDisparoRegraEtiqueta);

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
        return ['prod', 'dev', 'test'];
    }
}
