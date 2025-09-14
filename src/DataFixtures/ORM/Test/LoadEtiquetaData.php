<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadEtiquetaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta;

/**
 * Class LoadEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $etiqueta = new Etiqueta();
        $etiqueta->setNome('TESTE');
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(false);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));

        // Persist entity
        $manager->persist($etiqueta);

        // Create reference for later usage
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);

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
        return 2;
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
