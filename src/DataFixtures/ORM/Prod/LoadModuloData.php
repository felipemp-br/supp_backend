<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModuloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Modulo;

/**
 * Class LoadModuloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModuloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $modulo = $manager
            ->createQuery(
                "
                SELECT m 
                FROM SuppCore\AdministrativoBackend\Entity\Modulo m 
                WHERE m.nome = 'ADMINISTRATIVO'"
            )
            ->getOneOrNullResult() ?: new Modulo();

        $modulo->setNome('ADMINISTRATIVO');
        $modulo->setDescricao('MÓDULO ADMINISTRATIVO');
        $modulo->setSigla('AD');
        $modulo->setPrefixo('supp_core.administrativo_backend');
        $modulo->setAtivo(true);

        // Persist entity
        $manager->persist($modulo);

        $this->addReference('Modulo-'.$modulo->getNome(), $modulo);

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
        return [
            'prod', 'dev', 'test',
            'config-modulo-administrativo-prod',
            'config-modulo-administrativo-hom',
            'config-modulo-administrativo-dev',
        ];
    }
}
