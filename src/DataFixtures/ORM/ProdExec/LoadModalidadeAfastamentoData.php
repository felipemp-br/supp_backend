<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeAfastamentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAfastamento;

/**
 * Class LoadModalidadeAfastamentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeAfastamentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeAfastamento = new ModalidadeAfastamento();
        $modalidadeAfastamento->setValor('FÉRIAS');
        $modalidadeAfastamento->setDescricao('FÉRIAS');

        $manager->persist($modalidadeAfastamento);

        $this->addReference('ModalidadeAfastamento-'.$modalidadeAfastamento->getValor(), $modalidadeAfastamento);

        $modalidadeAfastamento = new ModalidadeAfastamento();
        $modalidadeAfastamento->setValor('LICENÇA');
        $modalidadeAfastamento->setDescricao('LICENÇA');

        $manager->persist($modalidadeAfastamento);

        $this->addReference('ModalidadeAfastamento-'.$modalidadeAfastamento->getValor(), $modalidadeAfastamento);

        $modalidadeAfastamento = new ModalidadeAfastamento();
        $modalidadeAfastamento->setValor('RECESSO');
        $modalidadeAfastamento->setDescricao('RECESSO');

        $manager->persist($modalidadeAfastamento);

        $this->addReference('ModalidadeAfastamento-'.$modalidadeAfastamento->getValor(), $modalidadeAfastamento);

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
