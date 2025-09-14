<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeAlvoInibidorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAlvoInibidor;

/**
 * Class LoadModalidadeAlvoInibidorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeAlvoInibidorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeAlvoInibidor = new ModalidadeAlvoInibidor();
        $modalidadeAlvoInibidor->setValor('INIBIÇÃO PARA IMPRESSÃO');
        $modalidadeAlvoInibidor->setDescricao('INIBIÇÃO PARA IMPRESSÃO');

        $manager->persist($modalidadeAlvoInibidor);

        $this->addReference('ModalidadeAlvoInibidor-'.$modalidadeAlvoInibidor->getValor(), $modalidadeAlvoInibidor);

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
