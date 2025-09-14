<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeRepresentanteData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepresentante;

/**
 * Class LoadModalidadeRepresentanteData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeRepresentanteData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeRepresentante = new ModalidadeRepresentante();
        $modalidadeRepresentante->setValor('ADVOGADO');
        $modalidadeRepresentante->setDescricao('ADVOGADO');

        $manager->persist($modalidadeRepresentante);

        $this->addReference('ModalidadeRepresentante-'.$modalidadeRepresentante->getValor(), $modalidadeRepresentante);

        $modalidadeRepresentante = new ModalidadeRepresentante();
        $modalidadeRepresentante->setValor('MINISTÉRIO PÚBLICO');
        $modalidadeRepresentante->setDescricao('MINISTÉRIO PÚBLICO');

        $manager->persist($modalidadeRepresentante);

        $this->addReference('ModalidadeRepresentante-'.$modalidadeRepresentante->getValor(), $modalidadeRepresentante);

        $modalidadeRepresentante = new ModalidadeRepresentante();
        $modalidadeRepresentante->setValor('DEFENSORIA PÚBLICA');
        $modalidadeRepresentante->setDescricao('DEFENSORIA PÚBLICA');

        $manager->persist($modalidadeRepresentante);

        $this->addReference('ModalidadeRepresentante-'.$modalidadeRepresentante->getValor(), $modalidadeRepresentante);

        $modalidadeRepresentante = new ModalidadeRepresentante();
        $modalidadeRepresentante->setValor('ESCRITÓRIO DE ADVOCACIA');
        $modalidadeRepresentante->setDescricao('ESCRITÓRIO DE ADVOCACIA');

        $manager->persist($modalidadeRepresentante);

        $this->addReference('ModalidadeRepresentante-'.$modalidadeRepresentante->getValor(), $modalidadeRepresentante);

        $modalidadeRepresentante = new ModalidadeRepresentante();
        $modalidadeRepresentante->setValor('ADVOCACIA PÚBLICA');
        $modalidadeRepresentante->setDescricao('ADVOCACIA PÚBLICA');

        $manager->persist($modalidadeRepresentante);

        $this->addReference('ModalidadeRepresentante-'.$modalidadeRepresentante->getValor(), $modalidadeRepresentante);

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
