<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeMeioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;

/**
 * Class LoadModalidadeMeioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeMeioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeMeio = new ModalidadeMeio();
        $modalidadeMeio->setValor('FÍSICO');
        $modalidadeMeio->setDescricao('FÍSICO');

        $manager->persist($modalidadeMeio);

        $this->addReference('ModalidadeMeio-'.$modalidadeMeio->getValor(), $modalidadeMeio);

        $modalidadeMeio = new ModalidadeMeio();
        $modalidadeMeio->setValor('ELETRÔNICO');
        $modalidadeMeio->setDescricao('ELETRÔNICO');

        $manager->persist($modalidadeMeio);

        $this->addReference('ModalidadeMeio-'.$modalidadeMeio->getValor(), $modalidadeMeio);

        $modalidadeMeio = new ModalidadeMeio();
        $modalidadeMeio->setValor('HÍBRIDO');
        $modalidadeMeio->setDescricao('HÍBRIDO');

        $manager->persist($modalidadeMeio);

        $this->addReference('ModalidadeMeio-'.$modalidadeMeio->getValor(), $modalidadeMeio);

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
