<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeRepositorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepositorio;

/**
 * Class LoadModalidadeRepositorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeRepositorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeRepositorio = new ModalidadeRepositorio();
        $modalidadeRepositorio->setValor('TESE');
        $modalidadeRepositorio->setDescricao('TESE');

        $manager->persist($modalidadeRepositorio);

        $this->addReference('ModalidadeRepositorio-'.$modalidadeRepositorio->getValor(), $modalidadeRepositorio);

        $modalidadeRepositorio = new ModalidadeRepositorio();
        $modalidadeRepositorio->setValor('JURISPRUDÊNCIA');
        $modalidadeRepositorio->setDescricao('JURISPRUDÊNCIA');

        $manager->persist($modalidadeRepositorio);

        $this->addReference('ModalidadeRepositorio-'.$modalidadeRepositorio->getValor(), $modalidadeRepositorio);

        $modalidadeRepositorio = new ModalidadeRepositorio();
        $modalidadeRepositorio->setValor('LEGISLAÇÃO');
        $modalidadeRepositorio->setDescricao('LEGISLAÇÃO');

        $manager->persist($modalidadeRepositorio);

        $this->addReference('ModalidadeRepositorio-'.$modalidadeRepositorio->getValor(), $modalidadeRepositorio);

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
