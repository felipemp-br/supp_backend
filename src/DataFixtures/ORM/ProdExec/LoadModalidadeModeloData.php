<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeModeloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo;

/**
 * Class LoadModalidadeModeloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeModeloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeModelo = new ModalidadeModelo();
        $modalidadeModelo->setValor('EM BRANCO');
        $modalidadeModelo->setDescricao('EM BRANCO');

        $manager->persist($modalidadeModelo);

        $this->addReference('ModalidadeModelo-'.$modalidadeModelo->getValor(), $modalidadeModelo);

        $modalidadeModelo = new ModalidadeModelo();
        $modalidadeModelo->setValor('INDIVIDUAL');
        $modalidadeModelo->setDescricao('INDIVIDUAL');

        $manager->persist($modalidadeModelo);

        $this->addReference('ModalidadeModelo-'.$modalidadeModelo->getValor(), $modalidadeModelo);

        $modalidadeModelo = new ModalidadeModelo();
        $modalidadeModelo->setValor('LOCAL');
        $modalidadeModelo->setDescricao('LOCAL');

        $manager->persist($modalidadeModelo);

        $this->addReference('ModalidadeModelo-'.$modalidadeModelo->getValor(), $modalidadeModelo);

        $modalidadeModelo = new ModalidadeModelo();
        $modalidadeModelo->setValor('NACIONAL');
        $modalidadeModelo->setDescricao('NACIONAL');

        $manager->persist($modalidadeModelo);

        $this->addReference('ModalidadeModelo-'.$modalidadeModelo->getValor(), $modalidadeModelo);

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
        return ['prodexec'];
    }
}
