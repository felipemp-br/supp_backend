<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadGrupoContatoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GrupoContato;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadGrupoContatoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGrupoContatoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $grupoContato = new GrupoContato();
        $grupoContato->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $grupoContato->setAtivo(true);
        $grupoContato->setNome('USUÁRIO');
        $grupoContato->setDescricao('GRUPO DE USUÁRIOS');

        // Persist entity
        $manager->persist($grupoContato);

        // Create reference for later usage
        $this->addReference('GrupoContato-'.$grupoContato->getNome(), $grupoContato);

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
        return 3;
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
