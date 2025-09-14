<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeFaseData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase;

/**
 * Class LoadModalidadeFaseData.
 */
class LoadModalidadeFaseData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeFase = new ModalidadeFase();
        $modalidadeFase->setValor('CORRENTE');
        $modalidadeFase->setDescricao('CORRENTE');

        $manager->persist($modalidadeFase);

        $this->addReference('ModalidadeFase-'.$modalidadeFase->getValor(), $modalidadeFase);

        $modalidadeFase = new ModalidadeFase();
        $modalidadeFase->setValor('INTERMEDIÁRIA');
        $modalidadeFase->setDescricao('INTERMEDIÁRIA');

        $manager->persist($modalidadeFase);

        $this->addReference('ModalidadeFase-'.$modalidadeFase->getValor(), $modalidadeFase);

        $modalidadeFase = new ModalidadeFase();
        $modalidadeFase->setValor('DEFINITIVA');
        $modalidadeFase->setDescricao('DEFINITIVA');

        $manager->persist($modalidadeFase);

        $this->addReference('ModalidadeFase-'.$modalidadeFase->getValor(), $modalidadeFase);

        $modalidadeFase = new ModalidadeFase();
        $modalidadeFase->setValor('ELIMINADO');
        $modalidadeFase->setDescricao('ELIMINADO');

        $manager->persist($modalidadeFase);

        $this->addReference('ModalidadeFase-'.$modalidadeFase->getValor(), $modalidadeFase);

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
