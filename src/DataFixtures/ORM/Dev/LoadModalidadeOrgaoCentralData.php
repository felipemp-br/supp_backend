<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/DevModalidadeOrgaoCentralData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;

/**
 * Class LoadModalidadeOrgaoCentralData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeOrgaoCentralData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('AGU');
        $modalidadeOrgaoCentral->setDescricao('ADVOCACIA-GERAL DA UNIÃO');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('PGF');
        $modalidadeOrgaoCentral->setDescricao('PROCURADORIA-GERAL FEDERAL');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('PGU');
        $modalidadeOrgaoCentral->setDescricao('PROCURADORIA-GERAL DA UNIÃO');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('CGU');
        $modalidadeOrgaoCentral->setDescricao('CONSULTORIA-GERAL DA UNIÃO');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('SGA');
        $modalidadeOrgaoCentral->setDescricao('SECRETARIA-GERAL DE ADMINISTRAÇÃO');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

        $modalidadeOrgaoCentral = new ModalidadeOrgaoCentral();
        $modalidadeOrgaoCentral->setValor('CGAU');
        $modalidadeOrgaoCentral->setDescricao('CORREGEDORIA-GERAL DA ADVOCIACIA DA UNIÃO');
        $manager->persist($modalidadeOrgaoCentral);
        $this->addReference('ModalidadeOrgaoCentral-'.$modalidadeOrgaoCentral->getValor(), $modalidadeOrgaoCentral);

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
        return 1;
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
