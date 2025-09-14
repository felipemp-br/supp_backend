<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeInteressadoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado;

/**
 * Class LoadModalidadeInteressadoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeInteressadoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeInteressado = new ModalidadeInteressado();
        $modalidadeInteressado->setValor('REQUERENTE (PÓLO ATIVO)');
        $modalidadeInteressado->setDescricao('REQUERENTE (PÓLO ATIVO)');

        $manager->persist($modalidadeInteressado);

        $this->addReference('ModalidadeInteressado-'.$modalidadeInteressado->getValor(), $modalidadeInteressado);

        $modalidadeInteressado = new ModalidadeInteressado();
        $modalidadeInteressado->setValor('REQUERIDO (PÓLO PASSIVO)');
        $modalidadeInteressado->setDescricao('REQUERIDO (PÓLO PASSIVO)');

        $manager->persist($modalidadeInteressado);

        $this->addReference('ModalidadeInteressado-'.$modalidadeInteressado->getValor(), $modalidadeInteressado);

        $modalidadeInteressado = new ModalidadeInteressado();
        $modalidadeInteressado->setValor('TERCEIRO');
        $modalidadeInteressado->setDescricao('TERCEIRO');

        $manager->persist($modalidadeInteressado);

        $this->addReference('ModalidadeInteressado-'.$modalidadeInteressado->getValor(), $modalidadeInteressado);

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
        return ['prod', 'dev', 'test'];
    }
}
