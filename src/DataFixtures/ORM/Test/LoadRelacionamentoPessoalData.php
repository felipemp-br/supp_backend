<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadRelacionamentoPessoalData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRelacionamentoPessoal;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\RelacionamentoPessoal;

/**
 * Class LoadRelacionamentoPessoalData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRelacionamentoPessoalData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $relacionamentoPessoal = new RelacionamentoPessoal();
        $relacionamentoPessoal->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));
        $relacionamentoPessoal->setPessoaRelacionada($this->getReference('Pessoa-12312312387', Pessoa::class));
        $relacionamentoPessoal->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $relacionamentoPessoal->setModalidadeRelacionamentoPessoal(
            $this->getReference('ModalidadeRelacionamentoPessoal-CURADORIA', ModalidadeRelacionamentoPessoal::class)
        );

        // Persist entity
        $manager->persist($relacionamentoPessoal);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
