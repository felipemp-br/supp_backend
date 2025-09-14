<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeVinculacaoProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Nome;
use SuppCore\AdministrativoBackend\Entity\Pessoa;

/**
 * Class LoadNomeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadNomeData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $nome = new Nome();
        $nome->setValor('NOME 1');
        $nome->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));
        $nome->setOrigemDados(null);

        $manager->persist($nome);

        $this->addReference('Nome-'.$nome->getValor(), $nome);

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
