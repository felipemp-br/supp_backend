<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadFavoritoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Favorito;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadFavoritoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadFavoritoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $favorito = new Favorito();
        $favorito->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));
        $favorito->setContext('processo_administrativo_especie_processo');
        $favorito->setLabel('EspecieProcesso');
        $favorito->setObjectClass('SuppCore\AdministrativoBackend\Entity\EspecieProcesso');
        $favorito->setObjectId(1);
        $favorito->setQtdUso(1);
        $favorito->setPrioritario(true);

        // Persist entity
        $manager->persist($favorito);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['testFavorito'];
    }
}
