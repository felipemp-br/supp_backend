<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadTransicaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTransicao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Transicao;

/**
 * Class LoadNomeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTransicaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $transicao = new Transicao();
        $transicao->setObservacao('OBSERVACAO_1');
        $transicao->setAcessoNegado(false);
        $transicao->setEdital('Edital 1');
        $transicao->setMetodo('Metodo 1');
        $transicao->setModalidadeTransicao(
            $this->getReference('ModalidadeTransicao-ARQUIVAMENTO', ModalidadeTransicao::class)
        );
        $transicao->setProcesso($this->getReference('Processo-00100000003202077', Processo::class));
        $manager->persist($transicao);

        $this->addReference('Transicao-'.$transicao->getObservacao(), $transicao);

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
        return 5;
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
