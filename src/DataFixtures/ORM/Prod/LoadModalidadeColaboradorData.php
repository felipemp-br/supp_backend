<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeColaboradorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeColaborador;

/**
 * Class LoadModalidadeColaboradorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeColaboradorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeColaborador = new ModalidadeColaborador();
        $modalidadeColaborador->setValor('MEMBRO');
        $modalidadeColaborador->setDescricao('MEMBRO');

        $manager->persist($modalidadeColaborador);

        $this->addReference('ModalidadeColaborador-'.$modalidadeColaborador->getValor(), $modalidadeColaborador);

        $modalidadeColaborador = new ModalidadeColaborador();
        $modalidadeColaborador->setValor('SERVIDOR');
        $modalidadeColaborador->setDescricao('SERVIDOR');

        $manager->persist($modalidadeColaborador);

        $this->addReference('ModalidadeColaborador-'.$modalidadeColaborador->getValor(), $modalidadeColaborador);

        $modalidadeColaborador = new ModalidadeColaborador();
        $modalidadeColaborador->setValor('ESTAGIÁRIO');
        $modalidadeColaborador->setDescricao('ESTAGIÁRIO');

        $manager->persist($modalidadeColaborador);

        $this->addReference('ModalidadeColaborador-'.$modalidadeColaborador->getValor(), $modalidadeColaborador);

        $modalidadeColaborador = new ModalidadeColaborador();
        $modalidadeColaborador->setValor('TERCEIRIZADO');
        $modalidadeColaborador->setDescricao('TERCEIRIZADO');

        $manager->persist($modalidadeColaborador);

        $this->addReference('ModalidadeColaborador-'.$modalidadeColaborador->getValor(), $modalidadeColaborador);

        // Flush database changes
        $manager->flush();
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

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
