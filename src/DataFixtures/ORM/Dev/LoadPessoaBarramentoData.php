<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadPessoaBarramentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeQualificacaoPessoa;
use SuppCore\AdministrativoBackend\Entity\Pessoa;

/**
 * Class LoadPessoaBarramentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadPessoaBarramentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pessoa = new Pessoa();
        $pessoa->setNome('SECRETARIA ESPECIAL DE DESBUROCRATIZAÇÃO, GESTÃO E GOVERNO DIGITAL');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA JURÍDICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(true);
        $this->addReference('Pessoa-30682', $pessoa);
        $manager->persist($pessoa);

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
        return ['dev', 'test'];
    }
}
