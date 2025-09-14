<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadModalidadeMeioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Usuario;

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

        $modalidadeMeio->setAtivo(true);

        /*
         * varias possibilidades de referências
         **/
        $modalidadeMeio->setValor('TESTE_1');
        $modalidadeMeio->setDescricao('ModalidadeMeio');
        $modalidadeMeio->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($modalidadeMeio);

        // Create reference for later usage
        $this->addReference('ModalidadeMeio-'.$modalidadeMeio->getValor(), $modalidadeMeio);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 6;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
